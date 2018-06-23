<?php
	//根据关联数组输出属性
    function strToAttrs($attrs){
        $result = '';
        foreach ($attrs as $attr) {
            if($attr['name'] == 'src'){//移动端只要这个src属性
                $result .= ' '.$attr['name'].'="'.$attr['value'].'" ';
            }
        }
        return $result;
    }
    //根据关联数组输出节点
    function strToTags($values,$isEchoBr = true,$isEchoImg = true){
        $result = '';
        if(is_array($values)){
            foreach ($values as $value) {
                if(!isset($value['tag'])){
                    if($value != '<br/>' && $value != '<br/><br/>'){
                        $result .= $value;
                    }
                }else{
                    $parentTag = $value['tag'];
                    if($parentTag == 'div'){
                        continue;
                    }else if($parentTag == 'br'){
                        if($isEchoBr == true){
                            $result .= '<br>';
                        }
                    }else if($parentTag == 'img' && $isEchoImg == false){
                        $result .= '&nbsp;[图片]&nbsp;';
                    }else{
                        $result .= '<'.$parentTag;
                        if(isset($value['attrs'])){
                            $result .= strToAttrs($value['attrs']);
                        }
                        $result .= '>';
                        $result .= strToTags($value['children'],$isEchoBr,$isEchoImg);
                        $result .= '</'.$parentTag.'>';
                    }
                }
            }
        }
        return $result;
    }