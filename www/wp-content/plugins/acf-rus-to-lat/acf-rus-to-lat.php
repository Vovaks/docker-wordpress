<?php
/*
Plugin Name: Advanced Custom Fields: Rus-To-Lat
Plugin URI: https://qcust.com/
Description: Дополнение для Advanced Custom Fields (ACF) - очищает имена дополнительных полей от ненужных символов и преобразует в латынь. Замечания и проблемы присылайте по контактам: <a href="http://qcust.com/">http://qcust.com/</a>.
Author: Andrey Pavluk <andreykashops@gmail.com>
Version: 1.0.2
*/ 


class ACF_RusToLat {
	
	function __construct(){
			
		add_action('acf/field_group/admin_enqueue_scripts', array($this, 'enqueue_script') );
		
	}
	
	
	
	
	/*
	*  enqueue_script
	*
	*  @description: 
	*  @since 1.0.0
	*  @created: 31/08/16
	*/
	function enqueue_script(){
		
		if( function_exists('wp_add_inline_script') ){
			wp_add_inline_script('acf-field-group', $this->script() );
		}else{
			add_action('admin_footer', array($this, 'script_to_footer'));
		}
		
		
	}
	
	
	
	
	/*
	*  script_to_footer
	*
	*  @description: 
	*  @since 1.0.0
	*  @created: 31/08/16
	*/
	function script_to_footer(){

		$script = $this->script();
		
		echo '<script type="text/javascript">'. $script .'</script>';
		
		
	}
	
	
	
	
	/*
	*  script
	*
	*  @description: Return script data
	*  @since 1.0.0
	*  @created: 31/08/16
	*  @update: 21/04/17
	*/
	function script(){
		
		ob_start();
		?>
		(function($){
			$(document).on('keyup change', '#acf_fields .field_form tr.field_name input.name', function(){
				
				if ( $(this).is(':focus') ){
					return false;
				}else{
					var val = $(this).val();
					val = replace_field( val );
					
					if ( val !== $(this).val() ) {
						$(this).val(val);
						$(this).closest('.field').find('div.field_meta td.field_name').text(val);
					}
				}
				
			});
			
			$(document).on('keyup change', '#acf-field-group-fields .acf-field-setting-name .field-name', function(){
				
				if ( $(this).is(':focus') ){
					return false;
				}else{
					var val = $(this).val();
					val = replace_field( val );
					
					if ( val !== $(this).val() ) {
						$(this).val(val);
						$(this).closest('.acf-field-object').find('li.li-field-key').text(val);
					}
				}
				
			});
			
			function replace_field( val ){
				console.log(val);
				val = $.trim(val);
				var table = {
					'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd', 'е': 'e', 'ё': 'e', 'ж': 'zh', 
					'з': 'z', 'и': 'i', 'й': 'j', 'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n',
					'о': 'o', 'п': 'p', 'р': 'r','с': 's', 'т': 't', 'у': 'u', 'ф': 'f', 'х': 'h',
					'ц': 'c', 'ч': 'ch', 'ш': 'sh', 'щ': 'sh','ъ': '', 'ы': 'y', 'ь': '', 'э': 'e', 'ю': 'yu', 'я': 'ya'
				}
				
				$.each( table, function(k, v){
					var regex = new RegExp( k, 'g' );
					val = val.replace( regex, v );
				});
				
				val = val.replace( /[^\w\d-_]/g, '' );
				val = val.replace( /_+/g, '_' );
				val = val.replace( /^_?(.*)$/g, '$1' );
				val = val.replace( /^(.*)_$/g, '$1' );
				
				return val;
			}
		})(jQuery)
		<?php
		$script = ob_get_contents();
		ob_end_clean();
		
		return $script;
		
	}
	
}

new ACF_RusToLat();
