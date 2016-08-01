<?php

/**
 * Created by PhpStorm.
 * User: Théo
 * Date: 26/07/2016
 * Time: 00:07
 */
class DashboardUtility
{
    public static function get_infobutton($hovertext){
        if(!empty($hovertext)) {
            return '<i class="fa fa-question-circle infobutton" title="' . translate($hovertext, 'yproject') . '"></i>';
        } else {
            return "";
        }
    }

    private static function has_class_icon($left_icon, $right_icon, $include_class_attr=true){
        $t = "";
        if($include_class_attr){
            if(!empty($left_icon)){$t .= 'class="left-icon" ';} else if(!empty($right_icon)){$t .= 'class="right-icon" ';}
        } else {
            if(!empty($left_icon)){$t .= ' left-icon ';} else if(!empty($right_icon)){$t .= ' right-icon ';}

        }
        return $t;
    }

    /**
     * Crée un champ de formulaire standardisé avec moult paramètres
     *
     * array['params'] Ensemble des paramètres du champ
     *          ['id']          string Id du champ de formulaire
     *          ['label']       string Titre affiché à côté du champ
     *          ['infobubble']  string Si non vide, un élément d'infobulle apparaît à côté du titre du champ
     *
     *          ['editable']    boolean Si Faux, le champ n'est pas éditable, la valeur est juste affichée, vrai par défaut
     *          ['visible']     boolean Si faux, le champ n'est pas affiché, vrai par défaut
     *          ['admin_theme'] boolean Si vrai, ajoute le thème admin sur le champ, faux par défaut
     *          ['warning']boolean Si vrai, ajoute une infobulle invitant à la prudence si le champ est éditable, faux par défaut
     *
     *          ['value']       string Valeur initiale du champ
     *          ['type']        string Type de formulaire disponible à choisir parmi : "text", "number", "select", "editor", "date"
     *          ['placeholder'] string placeholder du champ
     *          ['prefix']      string texte affiché juste avant le champ
     *          ['suffix']      string texte affiché juste après le champ
     *          ['left_icon']   string nom de l'icone Font Awesome affichée à l'intérieur du champ, à gauche
     *          ['right_icon']  string ...ou droite (par défaut, pour les dates, un calendrier est affiché)
     *
     *          ['max']         int maximum pour les input number
     *          ['min']         int minimum
     *          ['step']        float valeur de l'attribut step pour les input number
     *          ['options_id']  array Liste des valeurs correspondantes aux options affichées (si vide, remplacés par les noms)
     *          ['options_name'] array Liste des options du sélecteur affichées
     *
     *
     * @param array $params Ensemble des paramètres du champ (voir ci-dessus)
     * @param bool $display Affiche ou non le champ à la fin, affiche par défaut (impossible à empêcher avec un editor)
     * @return string le code HTML du champ
     */
    public static function create_field($params, $display=true){
        //Label options
        $id = $params["id"];
        $label = $params["label"];
        $infobubble = $params["infobubble"];

        //All input options
        if(isset($params["editable"])){$editable=$params["editable"];}else{$editable=true;};
        if(isset($params["visible"]) && ($params["visible"])==false){return "";};
        if(isset($params["admin_theme"])){$admin_theme=$params["admin_theme"];}else{$admin_theme=false;};
        if(isset($params["warning"])){$warning=$params["warning"];}else{$warning=false;};

        $initial_value = $params["value"];
        $type = $params["type"];
        $placeholder=$params["placeholder"];
        if(empty($placeholder) && ($type=='date' || $type=='datetime' )){$placeholder="aaaa-mm-jj";}
        $prefix=$params["prefix"];
        $suffix=$params["suffix"];

        if(isset($params["left_icon"])){
            $left_icon=$params["left_icon"];
        }else if(isset($params["right_icon"])){
            $right_icon=$params["right_icon"];
        }else if($type=='date' || $type=='datetime'){
            $left_icon="calendar";
        }


        //Number input options
        if(isset($params["max"])){$max=$params["max"];};
        if(isset($params["min"])){$min=$params["min"];};
        if(isset($params["step"])){$step=$params["step"];}else{$step=1;};

        //Select input options
        $options_id=$params["options_id"];
        $options_names=$params["options_names"];
        if(!empty($options_names)) {
            if (!empty($options_id)) {
                $options_list = array_combine($options_id, $options_names);
            } else {
                $options_list = array_combine($options_names, $options_names);
            }
        }

        //Create the label
        $text_field = '<div class="field ';
        if($admin_theme){$text_field .='admin-theme';}
        $text_field .='">'
            .'<label for="'.$id.'">'
            .translate($label,'yproject')
            .DashboardUtility::get_infobutton($infobubble);
        if($admin_theme){
            $text_field .='<i class="fa fa-unlock-alt infobutton" aria-hidden="true" 
            title="' . translate("Vous pouvez modifier ce champ en tant qu'administrateur WDG", 'yproject') . '"></i>';}
        if($warning && $editable){
            $text_field .='<i class="fa fa-exclamation-triangle infobutton" aria-hidden="true" 
            title="' . translate("Attention ce champ est normalement géré automatiquement, manipulez-le avec prudence", 'yproject') . '"></i>';}
        $text_field .='</label>'

            .'<span class="field-container">'.$prefix.'<span class="field-value">';
        if($editable){
            //Add the icon inside input
            if(!empty($left_icon)){
                $text_field .= '<i class="left fa fa-'.$left_icon.'" aria-hidden="true"></i>';
            } else if(!empty($right_icon)){
                $text_field .= '<i class="right fa fa-'.$right_icon.'" aria-hidden="true"></i>';
            }

            switch ($type){
                case 'text':
                    $text_field .= '<input type="text" '
                        .'name="'.$id.'" '
                        .'id="update_'.$id.'" '
                        .'placeholder="'.$placeholder.'" '
                        .'value="'.$initial_value.'" ';
                    $text_field .= DashboardUtility::has_class_icon($left_icon, $right_icon);
                    $text_field .= '" />';
                    break;
                case 'number':
                    $text_field .= '<input type="number" '
                        .'name="'.$id.'" '
                        .'id="update_'.$id.'" '
                        .'placeholder="'.$placeholder.'" '
                        .'step="'.$step.'" '
                        .'value="'.$initial_value.'" ';
                    if(isset($max)){$text_field .= 'max="'.$max.'" ';}
                    if(isset($min)){$text_field .= 'min="'.$min.'" ';}
                    $text_field .= DashboardUtility::has_class_icon($left_icon, $right_icon);
                    $text_field .= '" />';
                    break;
                case 'select':
                    $text_field .= '<select type="text" '
                        .'name="'.$id.'" '
                        .'id="update_'.$id.'" ';
                    $text_field .= DashboardUtility::has_class_icon($left_icon, $right_icon);
                    $text_field .= '" >';
                        foreach($options_list as $index=>$name){
                            $text_field .='<option value="'.$index.'" ';
                            if($index==$initial_value){
                                $text_field .= ' selected="selected" ';
                            }
                            $text_field .='>'.translate($name,'yproject').'</option>';
                        }
                        $text_field .= '</select>';

                    break;
                case 'editor':
                    echo $text_field;
                    $text_field = "";
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
                    break;
                case 'date':
                    $text_field .= '<input type="text"'
                        .'name="'.$id.'" '
                        .'class="adddatepicker '.DashboardUtility::has_class_icon($left_icon, $right_icon, false).'" '
                        .'id="update_'.$id.'" '
                        .'placeholder="'.$placeholder.'" '
                        .'value="'.$initial_value->format('Y-m-d').'" '
                        .'" />';
                    break;
                case 'datetime':
                    $text_field .= '<input type="text"'
                        .'name="'.$id.'" '
                        .'class="adddatepicker datetime '.DashboardUtility::has_class_icon($left_icon, $right_icon, false).'" '
                        .'id="update_'.$id.'" '
                        .'placeholder="'.$placeholder.'" '
                        .'value="'.$initial_value->format('Y-m-d').'" '
                        .'" />';

                    $text_field .= '<select class="timepicker" '.'id="update_h_'.$id.'">';
                    for($i=0;$i<=23;$i++){
                        $text_field .= '<option value="'.$i.'" ';
                        if($initial_value->format('G')==$i){$text_field .= ' selected="selected" ';}
                        $text_field .='>'.$i.'h</option>';
                    }
                    $text_field .= '</select>';

                    $text_field .= '<select class="timepicker" '.'id="update_m_'.$id.'">';
                    for($i=0;$i<=59;$i++){
                        $text_field .= '<option value="'.$i.'" ';
                        if(intval($initial_value->format('i'))==$i){$text_field .= ' selected="selected" ';}
                        $text_field .='>'.sprintf('%02d', $i).'</option>';
                    }
                    $text_field .= '</select>';
                    break;
                default:
                    $text_field .= $initial_value;
            }
        } else {
            switch ($type){
                case 'select':
                    $text_field .= translate($options_list[$initial_value],'yproject');
                    break;
                case 'date':
                    $text_field .= translate($initial_value->format('l')).' '.$initial_value->format('d-m-Y');
                    break;
                case 'datetime':
                    $text_field .= translate($initial_value->format('l')).' '.$initial_value->format('d-m-Y, G\hi');
                    break;
                default:
                    $text_field .= $initial_value;
            }

        }
        $text_field .= '</span>'.$suffix.'</span>'
            .'</div>';

        if($display){
            echo $text_field;
        }
        return $text_field;
    }

    /**
     * @param $id id du formulaire
     * @param bool $display Affiche ou non le bouton à la fin, affiche par défaut
     * @return string le code HTML du bouton
     */
    public static function create_save_button($id, $display=true){
        //Style/classes à retravailler....

        $text_field ='<p id="'.$id.'_button" class="align-center">
            <input type="submit" value="'.__("Enregistrer", 'yproject').'" class="button"/>
            </p>
            <p id="'.$id.'_loading" class="align-center" hidden>
            <img src="'.get_stylesheet_directory_uri().'/images/loading.gif" alt="chargement"/>
            </p>';

        if($display){
            echo $text_field;
        }
        return $text_field;
    }
}