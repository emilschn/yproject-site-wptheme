<?php
class DashboardUtility {
	
    public static function get_infobutton($hovertext, $display=false, $other_icon=""){
        if(!empty($hovertext)) {
            if(empty($other_icon)){
                $text = '<i class="fa fa-fw fa-question-circle infobutton"';
            } else {
                $text = '<i class="fa fa-fw fa-'.$other_icon.' infobutton"';
            }

            $text.=' aria-hidden="true"></i>';
            $text .= '<span class="tooltiptext">'.translate($hovertext, 'yproject').'</span>';
            if($display){
                print $text;
            }
            return $text;
        } else {
            return "";
        }
	}
	
	public static function add_help_item( $user, $item_name, $version ) {
		$items = array(
			'home'			=> '<a href="https://support.wedogood.co/g%C3%A9rer-ma-lev%C3%A9e-de-fonds/g%C3%A9rer-mon-tableau-de-bord-et-les-informations-de-ma-campagne/comment-utiliser-mon-tableau-de-bord" target="_blank">Comment utiliser mon tableau de bord ?</a>',
			'stats'			=> '<a href="https://support.wedogood.co/comment-utiliser-les-statistiques-du-tableau-de-bord" target="_blank">Comment utiliser les statistiques de mon tableau de bord ?</a>',
			'contacts'		=> '<a href="https://support.wedogood.co/comment-utiliser-mon-tableau-de-contacts" target="_blank">Comment utiliser mon tableau de contacts ?</a>',
			'news'			=> '<a href="https://support.wedogood.co/comment-animer-ma-campagne-de-financement/les-diff%C3%A9rents-supports-de-communication-pour-animer-ma-campagne/comment-publier-des-actualitc3a9s-sur-we-do-good" target="_blank">Comment publier des actualités sur WE DO GOOD ?</a>',
			'organization'	=> '<a href="https://support.wedogood.co/comment-authentifier-mon-entreprise" target="_blank">Comment authentifier mon entreprise ?</a>'
		);
		if ( !isset( $items[ $item_name ] ) ) {
			return;
		}

		if ( $user->has_removed_help_item( $item_name, $version ) ) {
			return;
		}

		$current_item = $items[ $item_name ];
		?>

		<div id="help-item-<?php echo $item_name; ?>" class="help-item">
			<span>
				<button type="button" class="help-item-remove" data-item-name="<?php echo $item_name; ?>" data-item-version="<?php echo $version; ?>">X</button>
				<?php echo $current_item; ?>
			</span>
		</div>

		<?php
	}

    public static function get_admin_infobutton($display=false){
        $infobutton = self::get_infobutton("Vous pouvez voir ce champ en tant qu'administrateur WDG",false,"unlock-alt");
        if ($display) {
            echo $infobutton;
        }
        return $infobutton;
    }
	
	public static function create_field_template_translator( $params, $display = TRUE ) {
		$buffer = FALSE;
		
        $type = $params[ 'type' ];
		$override_with_template = array( 'select', 'check', 'text', 'text-money', 'text-percent', 'number', 'date', 'file' );
		if ( in_array( $type, $override_with_template ) ) {
			$editable = ( isset( $params[ 'editable' ] ) ) ? $params[ 'editable' ] : TRUE;
			$warning = false;
			if ( $editable ) {
				$warning = ( isset( $params[ 'warning' ] ) ) ? __( "Cette option doit &ecirc;tre mani&eacute;e avec pr&eacute;caution !", 'yproject' ) : FALSE;
			} else {
				$type = 'not-editable';
			}
			$admin_theme = ( isset( $params[ 'admin_theme' ] ) ) ? $params[ 'admin_theme' ] : FALSE;
			
			global $wdg_current_field;
			$wdg_current_field = array(
				'name'			=> $params[ 'id' ],
				'type'			=> $type,
				'label'			=> $params[ 'label' ],
				'value'			=> $params[ 'value' ],
				'admin_theme'	=> $admin_theme,
				'warning'		=> $warning,
				'complementary_class'	=> $params[ 'complementary_class' ]
			);
			
			if ( isset( $params[ 'description' ] ) ) {
				$wdg_current_field[ 'description' ] = $params[ 'description' ];
			}
			
			switch ( $params[ 'type' ] ) {
				case 'select':
					$options_id = $params[ 'options_id' ];
					$options_names = $params[ 'options_names' ];
					if ( !empty( $options_names ) ) {
						if ( !empty( $options_id ) ) {
							$options_list = array_combine( $options_id, $options_names );
						} else {
							$options_list = array_combine( $options_names, $options_names );
						}
					}
					$wdg_current_field[ 'options' ] = $options_list;
					if ( !$editable ) {
						$wdg_current_field[ 'value' ] = $options_list[ $wdg_current_field[ 'value' ] ];
					}
					break;
					
				case 'check':
					$wdg_current_field[ 'type' ] = 'checkboxes';
					$wdg_current_field[ 'label' ] = '';
					$wdg_current_field[ 'options' ] = array(
						$params[ 'id' ] => $params[ 'label' ]
					);
					$wdg_current_field[ 'values' ] = array(
						$params[ 'value' ]
					);
					break;
				
				case 'date':
					$wdg_current_field[ 'value' ] = $wdg_current_field[ 'value' ]->format( 'd/m/Y' );
					break;
				
				case 'text-percent':
					$wdg_current_field[ 'unit' ] = $params[ 'unit' ];
					break;
			}
			
			ob_start();
			locate_template( array( 'common/forms/field.php' ), true, false );
			$buffer = ob_get_clean();
			
			if ( $display ) {
				echo $buffer;
			}
		}
		
		return $buffer;
	}

    /**
     * Crée un champ de formulaire standardisé avec moult paramètres
     *
     * array['params'] Ensemble des paramètres du champ
     *          ['id']          string Id du champ de formulaire
     *          ['label']       string Titre affiché à côté du champ
     *          ['infobubble']  string Si non vide, un élément d'infobulle apparaît à côté du titre du champ
     *          ['fillbubble']  string Si non vide, une bulle avec ce contenu apparaît lorsqu'on survole/sélectionne le champ
     *
     *          ['editable']    boolean Si Faux, le champ n'est pas éditable, la valeur est juste affichée, vrai par défaut
     *          ['visible']     boolean Si faux, le champ n'est pas affiché, vrai par défaut
     *          ['admin_theme'] boolean Si vrai, ajoute le thème admin sur le champ, faux par défaut
     *          ['warning']boolean Si vrai, ajoute une infobulle invitant à la prudence si le champ est éditable, faux par défaut
     *
     *          ['type']        string Type de formulaire disponible à choisir parmi :
     *              "text"      champ de texte classique
     *              "number"    champ de nombre
     *              "select"    sélecteur
     *              "editor"    éditeur de texte wordpress
     *              "date"      champ de texte avec plugin pour choisir une date
     *              "datetime"  champs pour choisir une date et une heure
     *              "link"      champ de texte spécialement pour une URL
     *              "check"     case à cocher
     *              "textarea"  champ de texte étendu
     *              "hidden"    valeur cachée
     *          ['value']       string Valeur initiale du champ
     *          ['default_display'] string Valeur affichée par défaut (si non-éditable)
     *
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
    public static function create_field( $params, $display = true ) {
		$visible = ( isset( $params[ 'visible' ] ) ) ? $params[ 'visible' ] : true;
		if ( !$visible ) {
			return '';
		}
		
		$translator_result = self::create_field_template_translator( $params, $display );
		if ( !empty( $translator_result ) ) {
			return $translator_result;
		}
		
        //Label options
        $type = $params[ 'type' ];
        $id = $params["id"];
        $label = $params["label"];
		
		$complementary_class = $params[ 'complementary_class' ];
		
        $infobubble = $params["infobubble"];
        $fillbubble = $params["fillbubble"];
        $fillbubble_class = " ";
        if(!empty($fillbubble)){
            $fillbubble_class = 'qtip-element ';
        }

        //All input options
        if(isset($params["editable"])){$editable=$params["editable"];}else{$editable=true;}
        if(isset($params["visible"]) && ($params["visible"])==false){return "";}
        if(isset($params["admin_theme"])){$admin_theme=$params["admin_theme"];}else{$admin_theme=false;}
        if(isset($params["warning"])){$warning=$params["warning"];}else{$warning=false;}

        $initial_value = $params["value"];
        $default_initial_value = $params["default_display"];

        $placeholder=$params["placeholder"];
        if(empty($placeholder) && ($type=='date' || $type=='datetime' )){$placeholder="jj/mm/yyyy";}
        $prefix=$params["prefix"];
        $suffix=$params["suffix"];

        //Icone (or default icon according to the input type)
        $left_icon = $right_icon = "";
        if(isset($params["left_icon"])){
            $left_icon=$params["left_icon"];
        }else if(isset($params["right_icon"])){
            $right_icon=$params["right_icon"];
        }else if($type=='date' || $type=='datetime'){
//            $left_icon="calendar";
        }else if($type=='link'){
//            $right_icon="link";
        }

        $icon_class = "";
        if(!empty($left_icon)){$icon_class .= ' left-icon ';} else if(!empty($right_icon)){$icon_class .= ' right-icon ';}

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

        //Is this an hidden input
        if($type=='hidden'){
            $text_field = '<span class="field-container">'.$prefix.'<span class="field field-value" data-type="'.$type.'" data-id="'.$id.'">';
            $text_field .= '<input type="hidden" '
                . 'name="' . $id . '" '
                . 'id="' . $id . '" '
                . 'value="' . $initial_value . '">';
            $text_field .= '</span></span>';
            if($display) echo $text_field;
            return $text_field;
        }


        $text_field = '<div class="field ';
        if($admin_theme){$text_field .='admin-theme';}
        $text_field .='">';

        //Is this a checkbox
        if ($type == 'check') {
            $text_field .='<span class="field-container field-type-'.$type.'"">'.$prefix.'<span class="field-value" data-type="'.$type.'" data-id="'.$id.'">
            <input type="checkbox"
                class="'.$fillbubble_class.$complementary_class.'"';
                if($initial_value){$text_field .='checked ';}
                if(!$editable){$text_field .='disabled ';}
                if($editable){$text_field .='id="'.$id.'"';}
                if($editable){$text_field .='name="'.$id.'"';}
            $text_field .= ">";
            if(!empty($fillbubble)){$text_field .= '<span class="tooltiptext">'.translate($fillbubble, 'yproject').'</span>';}
            $text_field .= "</span>$suffix</span>";
        }

        //Create the label
        $text_field .='<label for="update_'.$id.'"';
            if($type=='check'){$text_field.='class="long-label"';}
        $text_field .='>';
        $text_field .= translate($label,'yproject')
            .DashboardUtility::get_infobutton($infobubble);
        if($warning && $editable){
            $text_field .= self::get_infobutton("Attention ce champ est normalement géré automatiquement, manipulez-le avec prudence",false,"exclamation-triangle");}
        $text_field .='</label>';

        if($type!='check') {
            $text_field .= '<span class="field-container">' . $prefix . '<span class="field field-value" data-type="' . $type . '" data-id="'.$id.'">';

            if ($editable) {
                //Add the icon inside input
                if (!empty($left_icon)) {
                    $text_field .= '<i class="left fa fa-' . $left_icon . '" aria-hidden="true"></i>';
                } else if (!empty($right_icon)) {
                    $text_field .= '<i class="right fa fa-' . $right_icon . '" aria-hidden="true"></i>';
                }

                switch ($type) {
                    case 'text':
                        $text_field .= '<input type="text" '
                            . 'name="' . $id . '" '
                            . 'id="' . $id . '" '
                            . 'placeholder="' . $placeholder . '" '
                            . 'value="' . $initial_value . '" '
                            . 'class="'.$fillbubble_class.$complementary_class.$icon_class.'"';
                        $text_field .= '">';
                        break;
                    case 'textarea':
                        $text_field .= '<textarea rows="3" '
                            . 'name="' . $id . '" '
                            . 'id="' . $id . '" '
                            . 'placeholder="' . $placeholder . '" '
                            . 'class="'.$fillbubble_class.$complementary_class.$icon_class.'"'
                            .'">'
                            .$initial_value.'</textarea>';
                        break;
                    case 'number':
                        $text_field .= '<input type="number" '
                            . 'name="' . $id . '" '
                            . 'id="' . $id . '" '
                            . 'placeholder="' . $placeholder . '" '
                            . 'step="' . $step . '" '
                            . 'value="' . $initial_value . '" '
                            . 'class="'.$fillbubble_class.$complementary_class.$icon_class.'"';
                        if (isset($max)) {
                            $text_field .= 'max="' . $max . '" ';
                        }
                        if (isset($min)) {
                            $text_field .= 'min="' . $min . '" ';
                        }
                        $text_field .= '" />';
                        break;
                    case 'select':
                        $text_field .= '<select type="text" '
                            . 'name="' . $id . '" '
                            . 'id="' . $id . '" '
                            . 'class="'.$fillbubble_class.$complementary_class.$icon_class.'"'
                            . '" >';
                        foreach ($options_list as $index => $name) {
                            $text_field .= '<option value="' . $index . '" ';
                            if ($index == $initial_value) {
                                $text_field .= ' selected="selected" ';
                            }
                            $text_field .= '>' . translate($name, 'yproject') . '</option>';
                        }
                        $text_field .= '</select>';

                        break;
                    case 'editor':
                        echo $text_field;
                        $text_field = "";
                        wp_editor($initial_value, $id,
                            array(
                                'media_buttons' => true,
                                'quicktags' => false,
                                'tinymce' => array(
                                    'plugins' => 'wordpress, paste, wplink, textcolor',
                                    'paste_remove_styles' => true
                                )
                            )
                        );
                        break;
                    case 'date':
                        $text_field .= '<input type="text"'
                            . 'name="' . $id . '" '
                            . 'class="adddatepicker ' .$fillbubble_class.$complementary_class.$icon_class. '" '
                            . 'id="' . $id . '" '
                            . 'placeholder="' . $placeholder . '" '
                            . 'value="' . $initial_value->format('d/m/Y') . '" '
                            . '/>';
                        break;
                    case 'datetime':
                        $text_field .= '<input type="text"'
                            . 'name="' . $id . '" '
                            . 'class="adddatepicker datetime ' .$fillbubble_class.$complementary_class.$icon_class. '" '
                            . 'id="' . $id . '" '
                            . 'placeholder="' . $placeholder . '" '
                            . 'value="' . $initial_value->format('d/m/Y') . '" '
                            . '/>';

                        $text_field .= '<select class="timepicker" ' . 'id="' . $id . '_h">';
                        for ($i = 0; $i <= 23; $i++) {
                            $text_field .= '<option value="' . $i . '" ';
                            if ($initial_value->format('G') == $i) {
                                $text_field .= ' selected="selected" ';
                            }
                            $text_field .= '>' . $i . 'h</option>';
                        }
                        $text_field .= '</select>';

                        $text_field .= '<select class="timepicker" ' . 'id="' . $id . '_m">';
                        for ($i = 0; $i <= 59; $i++) {
                            $text_field .= '<option value="' . $i . '" ';
                            if (intval($initial_value->format('i')) == $i) {
                                $text_field .= ' selected="selected" ';
                            }
                            $text_field .= '>' . sprintf('%02d', $i) . '</option>';
                        }
                        $text_field .= '</select>';
                        break;
                    case 'link':
                        $text_field .= '<input type="text" '
                            . 'name="' . $id . '" '
                            . 'id="' . $id . '" '
                            . 'placeholder="' . $placeholder . '" '
                            . 'value="' . $initial_value . '" '
                            . 'class="'.$fillbubble_class.$complementary_class.$icon_class.'"/>';
                        break;
					case 'upload':
						$download_label = $params["download_label"];
                        $text_field .= '<input type="file" '
                            . 'name="' . $id . '" '
                            . 'id="' . $id . '" '
                            . 'class="'.$fillbubble_class.$complementary_class.$icon_class.'" /> ';
						if ( !empty($initial_value) ) {
							$text_field .= '<a href="'.$initial_value.'" download="'.$download_label.'">' .__("T&eacute;l&eacute;charger", 'yproject'). '</a>';
						} else {
							$text_field .= __("En attente d'envoi", 'yproject');
						}
						break;
                    default:
                        $text_field .= $initial_value;
                }

                if(!empty($fillbubble)){$text_field .= '<span class="tooltiptext">'.translate($fillbubble, 'yproject').'</span>';}
				
            } else {
                $text_field .= '<span class="non-editable">';
                switch ($type) {
                    case 'text':
                    case 'number':
                    case 'editor':
                    case 'textarea':
                        if(empty($initial_value)){
                            $text_field .= $default_initial_value;
                        } else {
                            $text_field .= $initial_value;
                        }
                        break;
                    case 'select':
                        $text_field .= translate($options_list[$initial_value], 'yproject');
                        break;
                    case 'date':
                        $text_field .= translate($initial_value->format('l')) . ' ' . $initial_value->format('d-m-Y');
                        break;
                    case 'datetime':
                        $text_field .= translate($initial_value->format('l')) . ' ' . $initial_value->format('d-m-Y, G\hi');
                        break;
                    case 'link':
                        if (!empty($initial_value)) {
                            $text_field .= '<a target="_blank" href="' . $initial_value . '">' . $initial_value .
                                '&nbsp;<i class="fa fa-fw fa-external-link" aria-hidden="true"></i></a>';
                        } else {
                            $text_field .= $default_initial_value;
                        }
                        break;
					case 'upload':
						if ( !empty($initial_value) ) {
							$text_field .= '<a href="'.$initial_value.'">' .__("T&eacute;l&eacute;charger", 'yproject'). '</a>';
						} else {
							$text_field .= __("En attente d'envoi", 'yproject');
						}
						break;
                }
                $text_field .= '</span>';
            }
            $text_field .= '</span>' . $suffix . '</span>';
            }
        $text_field .='</div>';

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
    public static function create_save_button( $id, $display = true, $initialText = 'Enregistrer', $waitingText = 'Enregistrement', $admin_theme = false ){
        $text_field ='<p class="align-center" id="'.$id.'_button">';
        $text_field .='<button type="submit" class="button '.($admin_theme ? 'admin-theme' : 'red').'">'
                .'<span class="button-text">'
                    .__($initialText, 'yproject')
                .'</span>'
                .'<span class="button-waiting" hidden>'
                    .'<i class="fa fa-spinner fa-spin fa-fw"></i>&nbsp;'.__($waitingText, 'yproject')
                .'</span>'
            .'</button>'
            .'</p>'
            .'<div class="feedback_save">'
                .'<span class="save_ok">Enregistré</span>'
                .'<span class="save_errors">Certaines informations sont invalides</span>'
                .'<span class="save_fail">Erreur de communication, réessayez plus tard</span>'
            .'</div>';

        if($display){
            echo $text_field;
        }
        return $text_field;
    }

    public static function check_enabled_page(){
        global $validated_or_after, $is_admin;
        if($is_admin && !$validated_or_after){
            echo 'class=admin-theme';
        } else if(!$validated_or_after) {
            echo 'class="disabled"';
        }
    }
}