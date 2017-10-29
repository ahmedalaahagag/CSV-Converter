<?php
/**
 * @license GPL3
 */

/**
 * Generates an HTML file out of supplied data.
 *
 */
class File_CSV_Converter_Strategy_ToHTML implements File_CSV_Converter_Strategy
{

  private
    $layout_filepath,
    $template_filepath;

  public function __construct(array $params = array())
  {
    // Perform sanity checks
    $this->performSanityChecks($params);

    // Configure strategy
    $this->layout_filepath = sprintf('%s/layout.html.php', $params['templates_dir']);
    $this->template_filepath = sprintf('%s/row.html.php', $params['templates_dir']);
  }

  /**
   * Template in "template_filepath" is rendered for each row. The resulting html is then
   * decorated using the template in "layout_filepath".
   *
   * @param array $data
   * @return string
   */
  public function convert(array $data, $destinationfile_url)
  {
    $html_results = array();
    $rownum = 1;
    foreach ($data as $row)
    {
      $decoded_row = array_map('utf8_decode', $row);
      $decoded_row['rownum'] = $rownum;
      extract($decoded_row);
      ob_start();
      ob_implicit_flush(0);
      require($this->template_filepath);

      $html_results[] = ob_get_clean();
      $rownum++;
    }
    ob_start();
    ob_implicit_flush(0);
    extract(array('content' => implode('', $html_results)));
    require($this->layout_filepath);
    $decorated_html = ob_get_clean();

    file_put_contents($destinationfile_url, $decorated_html);
  }

  public static function getCliCommandSpecification()
  {
    $spec = array(
      'name'          => 'to-html',
      'description'   => sprintf('converts a CSV file to an HTML file (provided by %s)', __CLASS__),
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

  private function performSanityChecks(array $params)
  {
    // Mandatory parameters
    if (!isset($params['templates_dir']))
    {
      throw new RuntimeException('Parameter "templates_dir" is mandatory');
    }

    // Templates must be readable
    // -- layout
    $layout_filepath = sprintf('%s/layout.html.php', $params['templates_dir']);
    if (!is_readable($layout_filepath))
    {
      throw new RuntimeException(sprintf('"%s" must be readable', $layout_filepath));
    }

    // -- row template
    $template_filepath = sprintf('%s/row.html.php', $params['templates_dir']);
    if (!is_readable($template_filepath))
    {
      throw new RuntimeException(sprintf('"%s" must be readable', $template_filepath));
    }

    return true;
  }
}
