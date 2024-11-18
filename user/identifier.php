<?php
function getBirthdateFromIdentity($identity) {
    //keluarkan date of birth terus daripada IC
    //substring identity to get bday
    $date = substr($identity, 0, 6);

        $date = str_split($date, 2);
        $currentCentury = floor(date('Y') / 100);
        if ($date[0] > date('y')) {
            // year is in last century
            $date[0] = ($currentCentury - 1) . $date[0];
        } else {
            $date[0] = $currentCentury . $date[0];
        }
        return implode("-", $date);
}


function getGenderFromIdentity($identity) {
    //identify male or female
    //substring gender data and convert it to int
    $gender = (int) substr($identity, 11, 1);
    
    return ($gender % 2 == 0) ? 'Female' : 'Male';
}


function getAgeFromBirthday( $birthdate) {
    //kira umur
    $bday = new DateTime($birthdate);
	$today = new Datetime(date('y.m.d'));
	if($bday>$today)
	{
		return 'No license available.';
	}
	$diff = $today->diff($bday);
	return ''.$diff->y.' Years, '.$diff->m.' month, '.$diff->d.' days';
}

function getPlaceOfBirth()
    {       
        return substr($this->id, 7, 2);
    }

