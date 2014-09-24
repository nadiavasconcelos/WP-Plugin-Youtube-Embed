<?php
/**
 * Plugin Name: [Mkt Virtual] Youtube Embed
 * Plugin URI: http://mktvirtual.com.br
 * Description: - Inclui player do youtube a partir da URL ou código do vídeo, através de shortcode.
 * Version: 1.0
 * Author: Nádia Vasconcelos
 * Author URI: http://mktvirtual.com.br
 * License: GPL2
 * Year: 2014
 */

if ( ! defined( 'ABSPATH' ) ) {
    die( "Can't load this file directly" );
}

class YoutubeEmbed{

    function __construct() {
        // Adiciona Shortcode
        add_shortcode('mktvirtual_youtube_player', array($this, 'shortcode_youtube_player'));

        // Cria o botão no editor
        add_action('media_buttons_context', array($this, 'add_ytplayer_button'), 30);
        // Cria pop-up que abre ao clicar no botão acima
        add_action('admin_footer', array($this, 'popup_youtube_player'), 30);
    }

    /*
     * Criação do Shortcode que gera o player
     */
    public function shortcode_youtube_player( $atts ) {
        // Opções customizáveis:
        // video => Código do vídeo
        // largura => padrão 572px
        // altura => padrão 380px
        $a = shortcode_atts( array(
            'video' => '',
            'largura' => '572',
            'altura' => '380'
        ), $atts );

        $embed = '';
        $width = $a['largura'];
        $height = $a['altura'];
        if( !empty($a['video']) ){
            $video_code = explode("v=", $a['video']);
            if( isset($video_code[1]) ){
                $embed = '<iframe width="'.$width.'" height="'.$height.'" src="//www.youtube.com/embed/'.$video_code[1].'" frameborder="0" allowfullscreen></iframe>';
            }else{
                $embed = '<iframe width="'.$width.'" height="'.$height.'" src="//www.youtube.com/embed/'.$video_code[0].'" frameborder="0" allowfullscreen></iframe>';
            }
        }
        return $embed;
    }

    /*
     * EDITOR: Criação do botão p/ adicionar o vídeo via URL
     */
    public function add_ytplayer_button($context) {
      // Ícone (dentro da pasta do plugin)
      $img = get_bloginfo('wpurl').'/wp-content/plugins/mkt-virtual-youtube-embed/imagens/youtube.png';

      $title = 'Shortcode Youtube Player';

      $context .= "<a title='{$title}' id='btnYt' href='#TB_inline?height=250&amp;width=450&amp;inlineId=popup_ytplayer' class='thickbox'>
          <img src='{$img}' /></a>";

      return $context;
    }

    /*
     * EDITOR: Criação do pop-up, ação do botão
     */
    public function popup_youtube_player() {
      ?>
      <div id="popup_ytplayer" style="display:none;">
        <h3>Adicione abaixo a URL ou o código do vídeo no Youtube</h3>
        <p>Exemplo: <em>https://www.youtube.com/watch?v=QYReEkP7uIs</em> ou <em>QYReEkP7uIs</em></p>
          <table>
            <tr>
              <td colspan="2">
                <label for="mktvirtual_youtube_player">&nbsp;URL: </label><br />
                <input type="text" id="yyvideocode" class="large-text ui-autocomplete-input" autofocus /><br />
              </td>
            </tr>
            <tr>
              <td>
                <label for="mktvirtual_youtube_player">&nbsp;Largura: </label><br />
                <input type="text" id="yyvideolargura" class="large-text ui-autocomplete-input" autofocus /><br />
                <small>Padrão: 572px</small>
              </td>
              <td>
                <label for="mktvirtual_youtube_player">&nbsp;Altura: </label><br />
                <input type="text" id="yyvideoaltura" class="large-text ui-autocomplete-input" autofocus /><br />
                <small>Padrão: 380px</small>
              </td>
            </tr>
          </table>
          <br />
          <input type="submit" value="Adicionar" class="button" onclick="insertImageMap();" style="margin:4px 0 0 1px;" />
        <script>
        function insertImageMap() {
            var video = document.getElementById('yyvideocode').value;
            var largura = document.getElementById('yyvideolargura').value;
            var altura  = document.getElementById('yyvideoaltura').value;

            var txtLargura = ( largura != "" ) ? ' largura="'+largura+'"' : '';
            var txtAltura  = ( altura != "" ) ? ' altura="'+altura+'"' : '';
            
            window.parent.send_to_editor('[mktvirtual_youtube_player video="'+ video +'"'+txtLargura+''+txtAltura+']');

            document.getElementById('yyvideocode').value = "";
            document.getElementById('yyvideolargura').value = "";
            document.getElementById('yyvideoaltura').value = "";
            window.parent.tb_remove();
        }
        </script>
      </div>
      <?php
    }

}

new YoutubeEmbed();