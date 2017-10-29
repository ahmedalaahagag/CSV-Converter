<?php
/**
 * @license GPL3
 */

/**
 * Generates an JSON file out of supplied data.
 *
 */
class File_CSV_Converter_Strategy_ToJSON implements File_CSV_Converter_Strategy
{


  public function __construct(array $params = array())
  {
  }

  /**
   * Loops on each row and then performs json encode
   *
   * @param array $data
   * @return string
   */
  public function convert(array $data, $destinationfile_url)
  {
      $json_results = array();
    $rownum = 1;
    foreach ($data as $row)
    {
      $decoded_row = array_map('utf8_decode', $row);
      $decoded_row['rownum'] = $rownum;
      $json_results[] = $decoded_row;
      $rownum++;
    }
    ob_start();
    file_put_contents($destinationfile_url, json_encode($json_results));
  }

  public static function getCliCommandSpecification()
  {
    $spec = array(
      'name'          => 'to-json',
      'description'   => sprintf('converts a CSV file to an Json file (provided by %s)', __CLASS__),
      'options'       => array(
        'templates_dir' => array(
          'short_name'  => '-t',
          'long_name'   => '--templates-dir',
          'description' => 'path to the directory containing the templates',
          'help_name'   => 'DIRECTORY')
      )
    );

    return $spec;
  }

}
