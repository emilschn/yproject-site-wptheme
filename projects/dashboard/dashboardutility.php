<?php

/**
 * Created by PhpStorm.
 * User: Théo
 * Date: 26/07/2016
 * Time: 00:07
 */
class DashboardUtility
{
    /**
     * Crée un champ de formulaire texte standardisé
     * @param $id Id du champ de formulaire
     * @param $label Titre affiché à côté du champ
     * @param $initial_value Valeur initiale du champ
     * @param string $infobubble Si non vide, un élément d'infobulle apparaît à côté du titre du champ
     * @param bool $editable Si Faux, le champ n'est pas éditable, la valeur est juste affichée
     * @param string $prefix Ecrit avant le champ (rien si vide)
     * @param string $placeholder Ecrit dans le champ lorsqu'il est vide (rien si vide)
     */
    public static function create_text_field($id, $label, $initial_value, $infobubble="", $editable = true, $prefix="", $placeholder=""){
        $text_field = '<div class="field"><label for="'.$id.'">'.translate($label,'yproject');
        if(!empty($infobubble)){
            $text_field .= ' <i class="infobutton" title="'.translate($infobubble,'yproject').'"></i>';
        }
        $text_field .= '</label>';
        if($editable){
            $text_field .= $prefix.'<input type="text"
            name="'.$id.'" 
            id="update_'.$id.'" 
            placeholder="'.$placeholder.'"
            value="'.$initial_value.'"/>';
        } else {
            $text_field .= $initial_value;
        }
        $text_field .= '</div>';
        print($text_field);
    }

    /**
     * Crée un champ de formulaire de nombre standardisé
     * @param $id Id du champ de formulaire
     * @param $label Titre affiché à côté du champ
     * @param $initial_value Valeur initiale du champ
     * @param int $min Valeur minimale
     * @param int $max Valeur maximal
     * @param int $step Pas entre chaque valeur (par défaut, nombres entiers, pas de 1)
     * @param string $unit Nom de l'unité (écrit après le champ)
     * @param string $infobubble Si non vide, un élément d'infobulle apparaît à côté du titre du champ
     * @param bool $editable Si Faux, le champ n'est pas éditable, la valeur est juste affichée
     */
    public static function create_number_field($id, $label, $initial_value, $min=null, $max=null, $step=1, $unit="", $infobubble="", $editable = true){
        $text_field = '<div class="field"><label for="'.$id.'">'.translate($label,'yproject');
        if(!empty($infobubble)){
            $text_field .= ' <i class="infobutton" title="'.translate($infobubble,'yproject').'"></i>';
        }
        $text_field .= '</label><span class="field-value">';
        if($editable){
            $text_field .= '<input type="number"
            name="'.$id.'" 
            id="update_'.$id.'" 
            value="'.$initial_value.'" 
            step="'.$step.'"';
            if(!empty($min)){$text_field .= 'min="'.$min.'"';}
            if(!empty($min)){$text_field .= 'max="'.$max.'"';}
            $text_field .='/> '.$unit;
        } else {
            $text_field .= $initial_value;
        }
        $text_field .= '</span></div>';
        print($text_field);
    }


    /**
     * Crée un champ de formulaire d'éditeur de texte wordpress
     * @param $id Id du champ de formulaire
     * @param $label Titre affiché au-dessus du champ
     * @param $initial_value Valeur initiale de l'éditeur
     * @param string $infobubble Si non vide, un élément d'infobulle apparaît à côté du titre du champ
     * @param bool $editable Si Faux, le champ n'est pas éditable, la valeur est juste affichée
     */
    public static function create_wpeditor_field($id, $label, $initial_value, $infobubble="", $editable = true){
        $text_field = '<div class="field"><label for="'.$id.'">'.translate($label,'yproject');
        if(!empty($infobubble)){
            $text_field .= ' <i class="infobutton" title="'.translate($infobubble,'yproject').'"></i>';
        }
        $text_field .= '</label><br/>';
        print($text_field);
        if($editable){
            wp_editor( $initial_value, 'update_'.$id,
                array(
                    'media_buttons' => true,
                    'quicktags'     => false,
                    'tinymce'       => array(
                        'plugins'				=> 'paste, wplink, textcolor',
                        'paste_remove_styles'   => true
                    )
                )
            );
        } else {
            echo $initial_value;
        }
        echo '</div>';
    }

    /**
     * Crée un champ de formulaire de date (avec un datepicker) standardisé
     * @param $id Id du champ de formulaire
     * @param $label Titre affiché à côté du champ
     * @param $initial_value Valeur initiale du champ
     * @param string $infobubble Si non vide, un élément d'infobulle apparaît à côté du titre du champ
     * @param bool $editable Si Faux, le champ n'est pas éditable, la valeur est juste affichée
     */
    public static function create_date_field($id, $label, $initial_value, $infobubble="", $editable = true){
        $text_field = '<div class="field"><label for="'.$id.'">'.translate($label,'yproject');
        if(!empty($infobubble)){
            $text_field .= ' <i class="infobutton" title="'.translate($infobubble,'yproject').'"></i>';
        }
        $text_field .= '</label>';
        if($editable){
            $text_field .= '<input type="text"
            name="'.$id.'" 
            id="update_'.$id.'"
            class="adddatepicker"
            value="'.$initial_value->format('Y-m-d').'"/>';
        } else {
            $text_field .= $initial_value->format('Y-m-d');
        }
        $text_field .= '</div>';
        print($text_field);
    }

    /**
     * Crée un champ de formulaire de date (avec un datepicker) standardisé
     * @param $id Id du champ de formulaire
     * @param $label Titre affiché à côté du champ
     * @param $options_names Liste des options affichées
     * @param $options_id Liste des valeurs correspondantes aux options affichées (si vide, remplacés par les noms)
     * @param string $initial_value Valeur sélectionnée
     * @param string $infobubble Si non vide, un élément d'infobulle apparaît à côté du titre du champ
     * @param bool $editable Si Faux, le champ n'est pas éditable, la valeur est juste affichée
     */
    public static function create_select_field($id, $label, $options_names, $options_id, $initial_value="", $infobubble="", $editable = true){
        $text_field = '<div class="field"><label for="'.$id.'">'.translate($label,'yproject');
        if(!empty($infobubble)){
            $text_field .= ' <i class="infobutton" title="'.translate($infobubble,'yproject').'"></i>';
        }
        $text_field .= '</label>';

        if(!empty($options_id)){
            $options_list = array_combine($options_id, $options_names);
        } else {
            $options_list = array_combine($options_names, $options_names);
        }

        if($editable){
            $text_field .= '<select type="text"
            name="'.$id.'" 
            id="update_'.$id.'">';
            foreach($options_list as $index=>$name){
                $text_field .='<option value="'.$index.'"';
                if($index==$initial_value){
                    $text_field .= ' selected="selected"';
                }
                $text_field .='>'.translate($name,'yproject').'</option>';
            }

            $text_field .= '</select>';
        } else {
            $text_field .=translate($options_list[$initial_value],'yproject');
        }
        $text_field .= '</div>';
        print($text_field);
    }
}