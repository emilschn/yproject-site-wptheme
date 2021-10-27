<?php
// Interne WDG : une question est posée dans un chan Slack à une ou plusieurs personnes
class WDGCoffeeMachine {
	private static $channel = "challenge-quotidien-automatique";
	private static $user_list;

	private static $list_question = array(
		"Pouvez-vous citer",
		"Pouvez-vous nous dire",
		"Et si vous nous donniez",
		"Est-ce que vous pouvez choisir",
		"Pouvez-vous partager",
	);

	private static $list_theme = array(
		'une chanson',
		'un film',
		'une série',
		'une citation (ou paroles de chanson)',
		'une recette',
		'un restaurant',
		'une ville',
		'un pays',
		'une activité',
		'un sport',
		'une photo',
		'une vidéo',
		'une personnalité',
		'un livre ou une BD',
		'une peinture',
		'une personne de votre entourage',
		'un dessin animé',
		'une chanteuse ou un chanteur',
		'un collègue',
		'un souvenir d’enfance',
		'un souvenir de jeunesse',
		'une bêtise',
		'un rêve',
		'un jeu',
		'un animal',
	);

	private static $list_actions = array(
		'que vous recommandez',
		'qui vous fait flipper',
		'que vous trouvez magnifique',
		'qui vous rend triste',
		'qui est un plaisir coupable',
		'que vous aimiez jeunes mais plus maintenant',
		'qui est un super souvenir',
		'qui vous file la pêche',
		'que vous voulez faire découvrir à %s',
		'qui ne plairait pas à %s',
		'qui vous rend dingue',
		'qui vous émeut',
		'qui vous questionne',
		'qui vous dégoute',
		'qui vous inspire',
		'de votre région ou ville natale',
		'que tout le monde aime mais pas vous',
		'que tout le monde déteste mais que vous adorez',
	);

	private static function send($message) {
		$url = YP_SLACK_WEBHOOK_URL_COFFEE;
		$room = self::$channel;
		$message = str_replace( '&', 'and', $message );
		$data = "payload=" . json_encode(array(
			"channel"       =>  "#{$room}",
			"text"          =>  $message
		));

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		$error = curl_error($ch);
		$errorno = curl_errno($ch);
		ypcf_debug_log( 'NotificationsSlack::send > ' . print_r( $result, true ) . ' ; ' . print_r( $error, true ) . ' ; ' . print_r( $errorno, true ), FALSE );
		curl_close($ch);
	}

	public static function get_funky() {
		$user_list_str = self::get_user_str();
		$question_str = self::get_question();
		$theme_str = self::get_theme();
		$action_str = self::get_action();
		$message = "Voici une question pour " . $user_list_str . " !\n";
		$message .= $question_str . " " . $theme_str . " " . $action_str . " ?";
		self::send( $message );
	}

	private static function get_user_str() {
		$user_list = self::get_user_list( 2 );
		$user_list_str = '<@' . $user_list[ 0 ] . '>';
		if ( count( $user_list ) ) {
			for ( $i = 1; $i < count( $user_list ); $i++ ) {
				$user_list_str .= ' et <@' . $user_list[ $i ] . '>';
			}
		}
		return $user_list_str;
	}

	private static function get_user_list( $nb ) {
		$buffer = array();
		if ( empty( self::$user_list ) ) {
			self::$user_list = explode( ',', YP_SLACK_COFFEE_USERS );
		}
		for ( $i = 0; $i < $nb; $i++ ) {
			$index_random = rand( 0, count( self::$user_list ) - 1 );
			$item = array_splice( self::$user_list, $index_random, 1 );
			array_push( $buffer, $item[ 0 ] );
		}
		return $buffer;
	}

	private static function get_question() {
		$index_random = rand( 0, count( self::$list_question ) - 1 );
		return self::$list_question[ $index_random ];
	}

	private static function get_theme() {
		$index_random = rand( 0, count( self::$list_theme ) - 1 );
		return self::$list_theme[ $index_random ];
	}

	private static function get_action() {
		$index_random = rand( 0, count( self::$list_actions ) - 1 );
		$action_str = self::$list_actions[ $index_random ];
		$user_target = self::get_user_list( 1 );
		$action_str = str_replace( '%s', '<@' . $user_target[ 0 ] . '>', $action_str );
		return $action_str;
	}

}