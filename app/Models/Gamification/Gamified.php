<?php

namespace App\Models\Gamification;

/**
 * This class controls the Gamified aspects of Arquigrafia.
 * It's used to inform the controllers which version is running (Gamified or Not-Gamified).
 */
class Gamified extends \Eloquent {
  /*
   * This function return the variation Id for a page based on user session.
   * @return  {Number}  "0" for non-gamified and "1" for gamefied
   */
  public static function getGamifiedVariationId() {
    if (\Session::has('gamified_variation_id')) {
      // Getting the variation Id
      $variationId = \Session::get('gamified_variation_id');
    } else {
      // If we don't have a variationId, we will define one and save on session
      $variationId = rand(0, 1);
      self::saveGamifiedVariationId($variationId);
    }

    return $variationId;
  }

  /**
   * This function saves the gamified variation id on Session
   * @params  {Number}  variationId  The variation id that you wanna save
   * @return  {Number}  Returns the variationId saved
   */
  public static function saveGamifiedVariationId($variationId) {
    // Saving on Session
    \Session::put('gamified_variation_id', $variationId);
    // Returns the saved variationId
    return \Session::get('gamified_variation_id');
  }

  /*
   * Just returns if a variation corresponds to a gamified version or not
   * @return  {Boolean}  true for gamified, false for non-gamified
   */
  public static function isGamified($variationId) {
    return $variationId == 1;
  }

  /**
   * Get the tag for gamified/non-gamified version
   * @return  {String}  VG for gamified and VN for non gamified
   */
  public static function getGamifiedVariationTag() {
    $variationId = self::getGamifiedVariationId();

    if ($variationId == 1) return 'VG';
    else return 'VN';
  }
}
