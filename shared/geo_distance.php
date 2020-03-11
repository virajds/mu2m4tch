<?php
class GeoDistanceCalc{
    // specify your own credentials
    private $km_multiplier = 111.189577;
    private $mile_multiplier = 69.09;
    public $distance_km;
    public $distance_miles;

    public function __construct()
    {
        $arguments = func_get_args();

        if(count($arguments) == 4) {
            $latitude_s = $arguments[0];
            $longitude_s = $arguments[1];
            $latitude_d = $arguments[2];
            $longitude_d = $arguments[3];

            if (($latitude_s == $latitude_d) && ($longitude_s == $longitude_d)) {
                $this->{'distance_km'} = 0;
                $this->{'distance_miles'} = 0;
            } else {
                $distance = sin(deg2rad($latitude_s)) * sin(deg2rad($latitude_d)) +  cos(deg2rad($latitude_s)) * cos(deg2rad($latitude_d)) * cos(deg2rad($longitude_s-$longitude_d));
                $distance = acos($distance);
                $distance = rad2deg($distance);
                $this->distance_km = round($distance * $this->km_multiplier, 2);
                $this->distance_miles = round($distance * $this->mile_multiplier, 2);
            }
        }
        else{
            $this->{'distance_km'} = 99999999999999;
            $this->{'distance_miles'} = 99999999999999;
        }
    }
}
?>