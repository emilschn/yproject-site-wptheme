<?php
class YPUIHelpers {
	/**
	 * Affiche les nombres de manière standardisée
	 * @param int $number
	 * @return string
	 */
	public static function display_number( $number, $money = FALSE ) {
		if ( empty( $number ) ) {
			return '0';
		}
		
		//Place des espaces entre les milliers
		$exceeds_one_thousand = floor( $number / 1000 );
		if ( $exceeds_one_thousand > 0 ) {
			$number_with_spaces = $exceeds_one_thousand .' '. YPUIHelpers::complete_number_str( $number % 1000 );
		} else {
			$number_with_spaces = $number;
		}
		
		//Remplace les points par des virgules
		$buffer = str_replace('.', ',', $number_with_spaces);
		
		//Si c'est de la monnaie, on voit pour ajouter un 0 pour les centimes, éventuellement
		if ( $money && strpos($buffer, ',') ) {
			$buffer_comma_exploded = explode( ',', $buffer );
			if (count($buffer_comma_exploded) < 2) {
				$buffer .= '0';
			}
		}
		
		return $buffer;
	}
	
	/**
	 * Ajoute des chiffres à gauche pour compléter les centaines et milliers, etc.
	 * @param int $number
	 * @param int $nb_str
	 * @return string
	 */
	public static function complete_number_str( $number, $nb_str = 3 ) {
		$buffer = $number;
		
		$n_test = 10;
		for ( $i = 2; $i <= $nb_str; $i++ ) {
			if ( $number < $n_test ) {
				$buffer = '0' . $buffer;
			}
			$n_test *= 10;
		}
		
		return $buffer;
	}
}
