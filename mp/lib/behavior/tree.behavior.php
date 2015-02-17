<?php
//http://www.sitepoint.com/hierarchical-data-database-3/
class treeBehavior extends Model {
    private $left    = 'lft';
    private $right   = 'rght';
    private $parent  = 'parent_id';


    public function load($model=null, $instant = 'tree') {
        $this->alias = $model->getAlias();
        $this->table = $model->getTable();

        $model->$instant = $this;

        return $this;
    }

    public function indent(&$data = array(), $spacer = '', $display = 'title') {
        $right  = array();
        $result = array();

        foreach ($data as $key => $row) {
            if (count($right) > 0) {
                // check if we should remove a node from the stack

                while (isset($right[count($right)-1]) && $right[count($right)-1] < $row[$this->alias][$this->right]) {
                    array_pop($right);
                }
            }

            // display indented node title
            $data[$key][$this->alias][$display] = $result[$key] = str_repeat($spacer, count($right)).$row[$this->alias][$display];

            $right[] = $row[$this->alias][$this->right];
        }

        return $result;
    }

    public function flat($root, $spacer = '', $display = 'title', $level = 1) {
        $option = array (
                        'select' => "{$this->alias}.{$this->left}, {$this->alias}.{$this->right}",
                        'where' => "{$this->alias}.id = '{$root}' AND {$this->alias}.deleted = 0",
                    );

        $root = $this->find($option, "first");
        if (empty($root)) {
            return array();
        }

        $option = array (
                    'select' => "{$this->alias}.id, {$this->alias}.{$display}, {$this->alias}.{$this->left}, {$this->alias}.{$this->right}",
                    'where'  => "{$this->alias}.{$this->left} BETWEEN {$root[$this->alias][$this->left]} AND {$root[$this->alias][$this->right]} AND {$this->alias}.deleted = 0",
                    'order'  => "{$this->alias}.{$this->left} ASC"
        );

        if ($level > 1) {
            $option['where'] = "{$root[$this->alias][$this->left]} < {$this->alias}.{$this->left} AND {$this->alias}.{$this->right} < {$root[$this->alias][$this->right]} AND {$this->alias}.deleted = 0";
        }

        $data = $this->find($option);

        return $this->indent($data, $spacer, $display);
    }

    //rebuild mttp tree
    public function rebuild() {
        $options = array(
                        'select' => "{$this->alias}.id, {$this->alias}.lft",
                        'where'  => "{$this->alias}.parent_id = 0",
        );

        $root = $this->find($options, 'first');
        if(empty($root)) {
            return false;
        }

        $lft = empty ($root[$this->alias]['lft']) ? 1 : $root[$this->alias]['lft'];
        $this->__rebuild($root[$this->alias]['id'], $lft);

        return true;
    }

    private function __rebuild($parent, $left) {
        // the right value of this node is the left value + 1
        $right = $left+1;

        // get all children of this node
        $option = array (
                        'select' => "{$this->alias}.id",
                        'where'  => "{$this->alias}.parent_id = '{$parent}' AND {$this->alias}.deleted = 0",
                        'order'  => "{$this->alias}.index"
        );

        $result = $this->find($option);

        foreach($result as $row) {
            $right = $this->_rebuild($row[$this->alias]['id'], $right);
        }

        $option = array(
                        'fields' => array($this->left => $left, $this->right => $right),
                        'where' => "id = ".$parent
                    );

        $this->update($option);

        return $right+1;
    }

    public function add($target, $info=array()) {
        $option = array (
                        'select' => "{$this->alias}.id, {$this->alias}.{$this->right}",
                        'where' => "{$this->alias}.id = '{$target}' AND {$this->alias}.deleted = 0",
        );

        $result = $this->find($option, "first");

        $flag = $result[$this->alias][$this->right];

        $option = array(
                        'fields' => array($this->right => "`".$this->right . "`+ 2"),
                        'where' => $this->right." >= ".$flag
                    );

        $this->update($option);

        $option = array(
                        'fields' => array($this->left => "`".$this->left . "` + 2"),
                        'where' => $this->left." >= ".$flag
                    );

        $this->update($option);

        $info[$this->left]  = $flag;
        $info[$this->right] = $flag + 1;
        $info['parent_id']  = $target;

        $this->create($info);
    }

    public function delete($target = '') {
        $option = array(
                        'select' => "{$this->alias}.{$this->left}, {$this->alias}.{$this->right}"
                    );

        $tmp = $result = $this->extract($target, false, $option);
        $result = Helper::getHelper('hash')->extract($result, $this->alias);
        $delta = count($result)*2;

        $flag = $tmp[$target][$this->alias][$this->right];

        $option = array(
                    'fields' => array($this->right => "`".$this->right . "`- ".$delta),
                    'where' => $this->right." >= ".$flag
        );
        $this->update($option);

        $option = array(
                        'fields' => array($this->left => "`".$this->left . "` - ".$delta),
                        'where' => $this->left." >= ".$flag
                    );
        $this->update($option);

        $update[$this->left] = 0;
        $update[$this->right] = 0;
        $update['deleted'] = 1;

        $option = array(
                        'fields' => $update,
                        'where'  => 'id IN ('.implode($result, ',').')'
                    );

        $this->update($option);
    }

    public function extract($id = '', $childOnly = false, $custom = array()) {
        $option = array(
                        'select' => "{$this->alias}.lft, {$this->alias}.rght",
                        'where' => "{$this->alias}.id = {$id} AND {$this->alias}.status > 0 AND {$this->alias}.deleted = 0",
                    );

        $parent = $this->find($option, 'first');
        if (empty($parent)) {
            return array();
        }

        extract($parent[$this->alias]);

        $where = "{$this->alias}.{$this->left} BETWEEN {$lft} AND {$rght} AND {$this->alias}.status > 0 AND {$this->alias}.deleted = 0";
        if ($childOnly) {
            $where = "{$lft} < {$this->alias}.{$this->left} AND {$this->left} < {$rght}  AND {$this->alias}.status > 0 AND {$this->alias}.deleted = 0";
        }

        $option = array (
                        'select' => "{$this->alias}.id, {$this->alias}.title",
                        'where'  => $where,
                        'order'  => "{$this->alias}.lft",
            );

        $option = array_merge($option, $custom);

        $option['select'] .= ", {$this->alias}.lft, {$this->alias}.rght";

        return $this->find($option);
    }

    //build tree from parent id
    public function build($data = array(), $root = 0) {
        $new = array();
        foreach ($data as $item) {
            $new[$item[$this->alias]['parent_id']][] = $item;
        }

        return $this->__build($new, array($data[$root]));
    }

    private function __build(&$list, $parent){
        $tree = array();

        foreach ($parent as $k => $l) {
            if (isset($list[$l[$this->alias]['id']])) {
                $l['children'] = $this->_build($list, $list[$l[$this->alias]['id']]);
            }

            $tree[] = $l;
        }

        return $tree;
    }
}