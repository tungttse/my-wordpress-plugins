<?php

define(DAKNETCORP_FILTERED_POST, 'daknetcorp_filtered_post');
define(DAKNETCORP_FILTERED_TAG , 'daknetcorp_filtered_tag');
define(DAKNETCORP_AUTO_TAG_CATEGORY_SETTING, 'daknetcorp_auto_tag_category_time_setting');

add_action( 'admin_menu', 'datc_register' );
function datc_register() {
    add_menu_page(
        __( 'Custom Menu Title', 'textdomaintextdomain' ),//textdomain
        'Tag Category',
        'manage_options',
        'daknetcorp-auto-tag-category/daknetcorp-auto-tag-category-admin.php',//menu slug
        'datc_datc_filter_category',//function
        plugins_url( 'daknetcorp-auto-tag-category/images/icon-daknetcorp.png' ),//icon url
        10
    );
}

function datc_datc_filter_category () { ?>
	<div class="wrap">
		<h2>Auto tag/category posts</h2>
		<input type="hidden" value="<?php echo plugins_url() ?>" id="daknetcorp_base_url">
		<?php  do_action('category-tab-daknetcorp'); ?>
		<div id="dialog-confirm" title="Delete keyword">
		  	Delete keyword
		</div>
		<div id="res_filter"></div>
		<div class="clr"></div>
	</div>
	<div class="clr"></div>

	<script type="text/javascript">
	    // POPUP INSERT FIELD
	    function popupform() {
			var docElem = window.document.documentElement, didScroll, scrollPosition;

			// trick to prevent scrolling when opening/closing button
			function noScrollFn() {
				window.scrollTo( scrollPosition ? scrollPosition.x : 0, scrollPosition ? scrollPosition.y : 0 );
			}

			function noScroll() {
				window.removeEventListener( 'scroll', scrollHandler );
				window.addEventListener( 'scroll', noScrollFn );
			}

			function scrollFn() {
				window.addEventListener( 'scroll', scrollHandler );
			}

			function canScroll() {
				window.removeEventListener( 'scroll', noScrollFn );
				scrollFn();
			}

			function scrollHandler() {
				if( !didScroll ) {
					didScroll = true;
					setTimeout( function() { scrollPage(); }, 60 );
				}
			};

			function scrollPage() {
				scrollPosition = { x : window.pageXOffset || docElem.scrollLeft, y : window.pageYOffset || docElem.scrollTop };
				didScroll = false;
			};

			scrollFn();

			[].slice.call( document.querySelectorAll( '.morph-button' ) ).forEach( function( bttn ) {
				new UIMorphingButton( bttn, {
					closeEl : '.icon-close',
					onBeforeOpen : function() {
						// don't allow to scroll
						noScroll();
					},
					onAfterOpen : function() {
						// can scroll again
						canScroll();
					},
					onBeforeClose : function() {
						// don't allow to scroll
						noScroll();
					},
					onAfterClose : function() {
						// can scroll again
						canScroll();
					}
				} );
			} );

			// for demo purposes only
			[].slice.call( document.querySelectorAll( 'form button' ) ).forEach( function( bttn ) { 
				bttn.addEventListener( 'click', function( ev ) { ev.preventDefault(); } );
			} );
		}
		// SHOW MESSAGE PROCESS
		function show_message(type){
			var i = -1;
			var toastCount = 0;
			var $toastlast; 

			if(type == 'success'){
				var getMessage = function () {
				    var msgs = ['Change Keyword Successfully!'];
				    return msgs;
				};
			} else if(type == 'delete_item') {
				var getMessage = function () {
				    var msgs = ['Delete Keyword Successfully!'];
				    return msgs;
				};
			} else if(type == 'add_item_error') {
				var getMessage = function () {
				    var msgs = ['Add Keyword Error!'];
				    return msgs;
				};
			} else if(type == 'add_item') {
				var getMessage = function () {
				    var msgs = ['Add Keyword Successfully!'];
				    return msgs;
				};
			} else if(type == 'filter_now') {
				var getMessage = function () {
				    var msgs = ['Posted are filtered Successfully!'];
				    return msgs;
				};
			} else if(type == 'update_option_daknetcorp') {
				var getMessage = function () {
				    var msgs = ['Update Setting General Successfully!'];
				    return msgs;
				};
			} else if(type == 'edit_item_error') {
				var getMessage = function () {
				    var msgs = ['Keywork is exists !.'];
				    return msgs;
				};
			} else {
				var getMessage = function () {
				    var msgs = ['message'];
				    return msgs;
				};
			}

			var getMessageWithClearButton = function (msg) {
			    msg = msg ? msg : 'Clear itself?';
			    msg += '<br /><br /><button type="button" class="btn clear">Yes</button>';
			    return msg;
			};
 
		    var shortCutFunction = 'success';//warning error info 
		    if(type == 'add_item_error') shortCutFunction = 'error';
		    if(type == 'edit_item_error') shortCutFunction = 'error';
		    var msg = jQuery('#message').val();
		    var title = jQuery('#title').val() || '';
		    var toastIndex = toastCount++;
		    var addClear = jQuery('#addClear').prop('checked');

		    toastr.options = {
		        closeButton: jQuery('#closeButton').prop('checked'),
		        debug: jQuery('#debugInfo').prop('checked'),
		        newestOnTop: jQuery('#newestOnTop').prop('checked'),
		        progressBar: jQuery('#progressBar').prop('checked'),
		        positionClass: jQuery('#positionGroup input:radio:checked').val() || 'toast-top-right',
		        preventDuplicates: jQuery('#preventDuplicates').prop('checked'),
		        onclick: null
		    };

		    if (jQuery('#addBehaviorOnToastClick').prop('checked')) {
		        toastr.options.onclick = function () {
		            alert('You can perform some custom action after a toast goes away');
		        };
		    }

		    toastr.options.showDuration = '300';
		    toastr.options.hideDuration = '1000';
		    toastr.options.timeOut = addClear ? 0 : '5000';
		    toastr.options.extendedTimeOut = addClear ? 0 : '1000';
		    toastr.options.showEasing = 'swing';
		    toastr.options.hideEasing = 'linear';
		    toastr.options.showMethod = 'fadeIn';
		    toastr.options.hideMethod = 'fadeOut';

		    if (addClear) {
		        msg = getMessageWithClearButton(msg);
		        toastr.options.tapToDismiss = false;
		    }
		    if (!msg) {
		        msg = getMessage();
		    }

		    jQuery('#toastrOptions').text('Command: toastr["'
		                    + shortCutFunction
		                    + '"]("'
		                    + msg
		                    + (title ? '", "' + title : '')
		                    + '")\n\ntoastr.options = '
		                    + JSON.stringify(toastr.options, null, 2)
		    );

		    var $toast = toastr[shortCutFunction](msg, title);  
		    $toastlast = $toast;

		    if(typeof $toast === 'undefined'){
		        return;
		    }

		    if ($toast.find('#okBtn').length) {
		        $toast.delegate('#okBtn', 'click', function () {
		            alert('you clicked me. i was toast #' + toastIndex + '. goodbye!');
		            $toast.remove();
		        });
		    }
		    if ($toast.find('#surpriseBtn').length) {
		        $toast.delegate('#surpriseBtn', 'click', function () {
		            alert('Surprise! you clicked me. i was toast #' + toastIndex + '. You could perform an action here.');
		        });
		    }
		    if ($toast.find('.clear').length) {
		        $toast.delegate('.clear', 'click', function () {
		            toastr.clear($toast, { force: true });
		        });
		    }
		}

  		// EDIT KEYWORD
  		function editFunction(parent, term_id, type){
			var id;
			jQuery('.box-edit').each(function(){
				id = jQuery(this).attr('id');
				removeElm(id);
		    });

			var getnode 	= document.getElementById('item-attribute-'+term_id);
			var checkNode 	= getnode.lastChild;
			var nodeName	= checkNode.nodeName;
			var valueText 	= jQuery('#input_hd'+term_id).val();
	 		var type_ 		= ','+ '\''+type + '\'';
			if(nodeName != 'DIV'){
				html = 	'<div class="box-edit" id="'+term_id+'">'+
							'<form action="#" method="post" name="box-edit-form" id="box-edit-form">'+
								'<input type="text" class="name-box" id="name-item-attr" name="name-item-attr" />'+
							'</form>'+
							'<div class="action-btn">'+
							 	'<span class="btn save_atrr" onClick="saveCategory('+term_id+type_+')">Save</span>'+
							 	'<span class="btn cancel_atrr" onClick="removeElm('+term_id+')">Cancel</span>'+
							'</div>'+
						'</div>';

				jQuery('#item-attribute-'+term_id).css('width','200px');
				jQuery('#term_'+type+term_id).parent().append(html);
				jQuery('#item-attribute-'+term_id+' .name-box').focus().val("").val(valueText);
			}
		}

		// REMOVE ELEMENT
		function removeElm(id){
			jQuery('#'+id).hide( "slow", function(){
				jQuery('#'+id).remove();
			});
			jQuery('#item-attribute-'+id).css('width','auto');
		}

		// SAVE KEYWORD
		function saveCategory(term_id, type){	
			var nameCate = jQuery('#'+term_id+' input.name-box').val();    
		    jQuery.ajax({
		        url 	: ajaxurl,
		        type 	:'post',
		        data 	: {
		            'action'	: 'datc_edit_item',
		            'dataSend'	: {'nameCate':nameCate,'term_id':term_id, 'type':type}
		        },
		        success	:function(data) {
		            dataEncode = jQuery.parseJSON(data);

		            if(dataEncode.error == 1){
						show_message('edit_item_error');
		            } else {
						jQuery('#input_hd'+term_id).val(dataEncode.nameCate);
			            jQuery('#term_'+ type +term_id).html(dataEncode.nameCate);
			            removeElm(term_id);
			            show_message('success');
		            }
		        },
		        error 	: function(errorThrown){
		            console.log(errorThrown);
		        }
		    });
		}

		// DELETE KEYWORD
		function deleteFunction(term_id, type){

	  		jQuery( "#dialog-confirm" ).dialog({
		      	resizable: false,
		      	height:140,
		      	modal: true,
		      	buttons: {
			        "Delete keyword": function() {
				        jQuery.ajax({
					        url 	: ajaxurl,
					        type 	:'post',
					        data 	: {
					            'action'	: 'datc_delete_item', 
					            'dataDel'	: {'term_id':term_id, 'type':type}
					        },
					        success	:function(data) {  
					        	jQuery('#item-attribute-'+term_id).fadeOut(600);	
					        	jQuery('#item-attribute-'+term_id).remove();
					            show_message('delete_item');
					        },
					        error 	: function(errorThrown){
					            console.log(errorThrown);
					        }
					    });
			          	jQuery( this ).dialog( "close" ); 
			        },
			        Cancel: function() {
			          	jQuery( this ).dialog( "close" );
			        }
		      	}
		    }); 
		}

		// ADD KEYWORD
		function addItem(parent_id,name_term, type){
    		var nameCate	= jQuery('#text-add-filter-'+parent_id).val();

			if(nameCate.length > 0){
				jQuery.ajax({
			        url 	: ajaxurl,
			        type 	:'post',
			        data 	: {
			            'action'	: 'datc_add_item', 
			            'dataAdd'	: {'parent_id':parent_id, 'nameCate':nameCate, 'name_term':name_term, 'type': type}
			        },
			        success	:function(data) {  
			        	dataEncode = jQuery.parseJSON(data); 
			        	if(dataEncode.error == 1){
			        		show_message('add_item_error');
			        		jQuery('#error_tag-'+parent_id).css('margin-bottom','-20px');
			        		jQuery('#error_tag-'+parent_id).css('display','block');
			        		jQuery('#error_tag-'+parent_id).html("Name is exists");
			        		jQuery('#error_tag-'+parent_id).fadeOut(3000, function(){
			        			jQuery('#error_tag-'+parent_id).html("");
			        		});
			        	} else if(dataEncode.error == 0){ 
			        		var html = '';
			        		html = '<div class="item-attribute" id="item-attribute-'+dataEncode.term_id_new_add+'">'+//term_id_new_add		
					    				'<span class="name-item" id="term_'+dataEncode.type_+dataEncode.term_id_new_add+'">'+dataEncode.nameCate+'</span>'+  //term_id_new_add
							    		'<input type="hidden" class="hidden" value="'+dataEncode.nameCate+'" id="input_hd'+dataEncode.term_id_new_add+'">'+ //term_id_new_add
							    		'<span class="action-item" parent="'+dataEncode.parent_term+'" term_id="'+dataEncode.term_id_new_add+'">'+  // term_id_new_add + parent_term
							    			'<img class="btn edit" onclick="editFunction('+dataEncode.parent_term+','+dataEncode.term_id_new_add+', \''+dataEncode.type_+'\')" src="'+jQuery("#daknetcorp_base_url").val()+'/daknetcorp-auto-tag-category/images/edit.png" alt="Sửa" title="Edit arttribute">'+
							    		 	'<img class="btn delete" onclick="deleteFunction('+dataEncode.term_id_new_add+', \''+dataEncode.type_+'\')" src="'+jQuery("#daknetcorp_base_url").val()+'/daknetcorp-auto-tag-category/images/remove.png" alt="Xóa" title="Delete arttribute">'+
							    		'</span>'+
							    	'</div>';

							jQuery('#td_tag_'+dataEncode.parent_term).before(html);
							jQuery('.morph-button').removeClass('open');
			        		show_message('add_item');
			        	} else if(dataEncode.error == 2){
			        		console.log(dataEncode.qur);
			        		console.log(dataEncode.idterm);
			        	}
			        },
			        error 	: function(errorThrown){
			            console.log(errorThrown);
			        }
			    });
			} else { 
				jQuery('#error_tag-'+parent_id).css('margin-bottom','-20px');
        		jQuery('#error_tag-'+parent_id).html("Keyword is not null"); 
			}
    	}

	</script>
	<?php
}

add_action('category-tab-daknetcorp', 'datc_tag_category');
function datc_tag_category(){ ?>
    <form name="form1" method="post" action="">
        <h2 class="nav-tab-wrapper" id="nav-tag-category">
            <a href="?page=daknetcorp-auto-tag-category/daknetcorp-auto-tag-category-admin.php&amp;tab=category" id="category" class="nav-tab nav-tab-active">Category</a>
            <a href="?page=daknetcorp-auto-tag-category/daknetcorp-auto-tag-category-admin.php&amp;tab=tag" id="tag" class="nav-tab">tag</a>     
        </h2> 
        <div class="wrap-category-tag active" id="wrap-category"> 
        	<h2>List category</h2>
        	<?php do_action('tag-category-list'); ?> 
        </div>
        <div class="wrap-category-tag" id="wrap-tag"> 
        	<h2>List tag category</h2>
        	<?php do_action('tag-category'); ?>
        </div> 
    </form>  

	<script type="text/javascript">
		jQuery(document).ready(function(){ 
			var url = (window.location).href;
			var tab = url.substring(url.lastIndexOf('tab=') + 4);

			jQuery('.wrap-category-tag').addClass('hidden');
			jQuery('.active').removeClass('hidden'); 
			jQuery('.wrap-category-tag').addClass('active');

			if("<?php echo $_GET['tab'] ?>"  != '' ){
				jQuery('#nav-tag-category a').each(function(){
					jQuery('#nav-tag-category a').removeClass('nav-tab-active');
				}); 
				jQuery('#'+tab).addClass('nav-tab-active');

				jQuery('.wrap-category-tag').each(function(){
					jQuery('.wrap-category-tag').addClass('hidden');
				}); 
				jQuery('#wrap-'+tab).removeClass('hidden'); 
			}  
		});
	</script>
<?php }

add_action('tag-category-list', 'datc_list_category');

function datc_list_category(){
	include_once( ABSPATH . 'wp-includes/wp-db.php' );
	global $wpdb;

	$term 			= $wpdb->prefix .'terms';
	$term_taxonomy 	= $wpdb->prefix .'term_taxonomy';
	$q_listcate = "SELECT t.term_id, t.name 
					FROM $term as t INNER JOIN $term_taxonomy as x ON t.term_id = x.term_id 
					WHERE t.slug <> 'uncategorized' AND x.taxonomy = 'category' order by t.name";
	$results = $wpdb->get_results($q_listcate);

	$edit 	 = plugins_url( 'daknetcorp-auto-tag-category/images/edit.png' );
	$del 	 = plugins_url( 'daknetcorp-auto-tag-category/images/remove.png' );
	$add 	 = plugins_url( 'daknetcorp-auto-tag-category/images/add.png' );
	?>
	<div class="wrap-list">
		<table class="list-category">
	        <tbody>
	            <tr>
	                <th class="name">Category</th> 
	                <th class="list-item">Keywords filter (the post must have all bellow keywords)</th> 
	            </tr>
				
	            <?php 
				//tt: get list category keyword filter.
				foreach ($results as $item) : 
	            	$term_id = $item->term_id;
	            	$query 	 = array();
	            	$query[] = "SELECT `term`.`name`, `term`.`term_id`,`taxo`.`parent` FROM `$term` as `term`";
	            	$query[] = "INNER JOIN `$term_taxonomy` as `taxo` ON `term`.`term_id` = `taxo`.`term_id`";
	            	$query[] = "WHERE `taxo`.`parent` =  '$term_id'";
	            	$query 	 = implode(' ', $query);  
	            	$arttribute_item = $wpdb->get_results($query); 
	            ?>
	            <tr>
	                <td class="name"><?php echo $item->name; ?></td> 
	                <td class="list-item"> 
	                	<?php if(!empty($arttribute_item)):?>
	                		<?php foreach ($arttribute_item as $att) : ?>
								<div class="item-attribute" id="item-attribute-<?php echo $att->term_id; ?>">
			                		<span class="name-item" id="term_<?php echo $att->term_id; ?>"><?php echo $att->name;?></span>  
			                		<input type="hidden" class="hidden" value="<?php echo $att->name;?>" id="input_hd<?php echo $att->term_id; ?>">
			                		<span class="action-item" parent="<?php echo $att->parent;?>" term_id="<?php echo $att->term_id;?>"> 
			                			<img class="btn edit_cat" src="<?php echo $edit; ?>" alt="Sửa" title="Edit arttribute" />
			                		 	<img class="btn delete_cat" src="<?php echo $del; ?>" alt="Xóa" title="Delete arttribute" />
			                		</span>
			                	</div>
	                		<?php endforeach; ?>
	                	<?php endif; ?> 

	                	<div class="mockup-content" id="td_tag_<?php echo $term_id; ?>">
							<div class="morph-button morph-button-modal morph-button-modal-2 morph-button-fixed">
								<button type="button"><img class="btn add add_new_field" src="<?php echo $add; ?>" alt="Add" title="Add arttribute" /> </button>
								<div class="morph-content"> 
									<div class="content-style-form content-style-form-1">
										<span class="icon icon-close">Close the dialog</span> 
										<h2>Add new filter</h2>
										<form>
											<p><label>Name filter</label>
											<input type="text" name="text-add-filter" id="text-add-filter-<?php echo $item->term_id; ?>" /></p>
											<p class="error" id="error_tag-<?php echo $item->term_id; ?>"></p>
											<p name_term="<?php echo $item->name; ?>" parent_id="<?php echo $item->term_id; ?>"><button class="button-save">Save</button></p>											
										</form>
									</div> 
								</div>
							</div>  
						</div> 
	                </td>   
	            </tr>
	            <?php endforeach; ?> 
	        </tbody>
	    </table>
    </div>
    <?php
		
		//tt: if no category, require create.
		if(empty($results) || sizeof($results) == 0) {
			$root=(isset($_SERVER['HTTPS']) ? "https://" : "http://").$_SERVER['HTTP_HOST'] . '/wp-admin/edit-tags.php?taxonomy=category';
    		echo "<p class='msg-create-tc'>Oop, you don't have any category yet, please create them in <a target='_blank' href='$root'>here</a></p>";
		}
			
	?>
    <script type="text/javascript"> 
		var type 		= 'category'; 
		jQuery('.edit_cat').click(function(){  
			var parent 		= jQuery(this).parent().attr('parent');
			var term_id 	= jQuery(this).parent().attr('term_id');
			var type 		= '';
			editFunction(parent, term_id, type);
		});

		jQuery('.delete_cat').click(function(){
			var term_id = jQuery(this).parent().attr('term_id'); 
			deleteFunction(term_id, type); 
		});

		jQuery('.add_new_field').click(function(){
			jQuery('.error').css('margin-bottom','0px');
			jQuery('.error').html("");
			popupform();
		});

		jQuery('.button-save').click(function(){
			var parent_id 	= jQuery(this).parent().attr('parent_id'); // termID parent
			var name_term 	= jQuery(this).parent().attr('name_term'); // nameTerm parent
			addItem(parent_id,name_term, type);
		});
    </script>
<?php } // end function show category and keyword filter.

add_action('tag-category','datc_list_tag');

function datc_list_tag(){ 
	include_once( ABSPATH . 'wp-includes/wp-db.php' );
	global $wpdb;

	$term 			= $wpdb->prefix .'terms';
	$term_taxonomy 	= $wpdb->prefix .'term_taxonomy';   
	$results = $wpdb->get_results("SELECT `t`.`term_id`,`t`.`name` FROM `$term` as `t` INNER JOIN `$term_taxonomy` as `x` ON `t`.`term_id` = `x`.`term_id` WHERE `x`.`taxonomy` = 'post_tag' order by `t`.`name`"); 

	//TODO: gom lai with category.
	$edit 	 = plugins_url( 'daknetcorp-auto-tag-category/images/edit.png' );
	$del 	 = plugins_url( 'daknetcorp-auto-tag-category/images/remove.png' );
	$add 	 = plugins_url( 'daknetcorp-auto-tag-category/images/add.png' ); ?>

	<table class="list-category">
        <tbody>
            <tr>
                <th class="name">Tag Name</th> 
                <th class="list-item">Keywords filter (the post must have all bellow keywords)</th>
            </tr>
            <?php foreach ($results as $item) : 
            	$term_id = $item->term_id;
            	$query 	 = array();
            	$query[] = "SELECT `term`.`name`, `term`.`term_id`,`taxo`.`parent` FROM `$term` as `term`";
            	$query[] = "INNER JOIN `$term_taxonomy` as `taxo` ON `term`.`term_id` = `taxo`.`term_id`";
            	$query[] = "WHERE `taxo`.`parent` =  '$term_id'";
            	$query 	 = implode(' ', $query);  
            	$attr_keyword = $wpdb->get_results($query); 
            ?>
            <tr>
                <td class="name"><?=$item->name?></td> 
                <td class="list-item"> 
	                <?php if(!empty($attr_keyword)):?>
		                <?php foreach ($attr_keyword as $keyword) : ?> 
	                	<div class="item-attribute" id="item-attribute-<?=$keyword->term_id?>" > 
	                		<span class="name-item" id="term_tag<?=$keyword->term_id?>"><?=$keyword->name;?></span> 
	                		<input type="hidden" class="hidden" value="<?=$keyword->name?>" id="input_hd<?=$keyword->term_id?>">
	                		<span class="action-item" parent="<?=$keyword->parent?>" term_id="<?=$keyword->term_id?>"> 
	                			<img class="btn edit_tag" src="<?=$edit?>" alt="Sửa" title="Edit arttribute">
	                		 	<img class="btn delete" src="<?=$del?>" alt="Xóa" title="Delete arttribute">
	                		</span>
	                	</div> 
	                	<?php endforeach; ?>
		            <?php endif; ?> 

                	<div class="mockup-content" id="td_tag_<?=$term_id?>">
						<div class="morph-button morph-button-modal morph-button-modal-2 morph-button-fixed">
							<button type="button"><img class="btn add add_new_field" src="<?=$add?>" alt="Add" title="Add arttribute"> </button>
							<div class="morph-content"> 
								<div class="content-style-form content-style-form-1">
									<span class="icon icon-close">Close the dialog</span> 
									<h2>Add new filter</h2>
										<p><label>Name filter</label>
										<input type="text" name="text-add-filter" id="text-add-filter-<?=$item->term_id?>" ></p>
										<p class="error" id="error_tag-<?=$item->term_id?>"></p>
										<p nameTerm="<?=$item->name?>" Termid="<?=$item->term_id?>"><button class="button-save btnSaveTag">Save</button></p>											
								</div> 
							</div>
						</div>
					</div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php
		// if no tag, require create.
		if(empty($results)) {
			$root=(isset($_SERVER['HTTPS']) ? "https://" : "http://").$_SERVER['HTTP_HOST'] . '/wp-admin/edit-tags.php?taxonomy=post_tag';
    		echo "<p class='msg-create-tc'>Oop, you don't have any tag yet, please create them in <a target='_blank' href='$root'>here</a></p>";
		}
			
	?>
    <script type="text/javascript">
    	jQuery('.add_new_field').click(function(){
			jQuery('.error').css('margin-bottom','0px');
			jQuery('.error').html("");
			popupform();  	
		});
		jQuery('.btnSaveTag').click(function(){
			var parent_id 	= jQuery(this).parent().attr('Termid'); // termID parent
			var name_term 	= jQuery(this).parent().attr('nameTerm'); // nameTerm parent
			var type 		= 'tag';
			addItem(parent_id,name_term, type);
		}); 

		jQuery('.delete').click(function(){
			var term_id = jQuery(this).parent().attr('term_id'); 
			var type 		= 'tag'; 
			deleteFunction(term_id, type); 
		});

		jQuery('.edit_tag').click(function(){  
			var parent 		= jQuery(this).parent().attr('parent');
			var term_id 	= jQuery(this).parent().attr('term_id');
			var type 		= 'tag';

			editFunction(parent, term_id, type);
		});
	</script>
<?php } // end show tag and keyword filter for tags.

add_action('category-tab-daknetcorp','datc_setup_category_filter');
function datc_setup_category_filter(){ ?> 
	<?php $op_daknet = get_option(DAKNETCORP_AUTO_TAG_CATEGORY_SETTING); ?>
    <?php $arr_setting = json_decode($op_daknet); ?> 

	<hr class="hr_ngang" />
	<h2>General settings</h2>
	<div class="set_category">
		<table class="form-table">
            <tbody> 
                <tr valign="top">
                    <th scope="row">Cronjob schedule</th>
                    <td>
                        <input id="daknetcorp_cache_time" name="daknetcorp_cache_time" type="text" value="<?=$arr_setting->time?>" size="4">
                        <select id="daknetcorp_cache_time_unit" name="daknetcorp_cache_time_unit">
                            <option value="minutes" selected="selected">Minutes</option>
                            <option value="hours">Hours</option>
                            <option value="days">Days</option>
                          <!--   <option value="weeky">Weeky</option>
                            <option value="monthly">Monthly</option> -->
                        </select> 
                        <span id="daknetcorp_cache_time_errmsg"></span><i style="color: #666; font-size: 11px;">Eg. 30 minutes </i> 
                        <a class="daknetcorp-tooltip-link" href="JavaScript:void(0);">What does this mean?</a>
                        <p class="des-daknetcorp">The schedule for the plugin runs to arrange posts.</p>
                    </td>
                </tr>  
                <tr valign="top">
                    <th scope="row">Limit of keyword for filter</th>
                    <td>
                        <input id="daknetcorp_num_show" name="daknetcorp_num_show" type="text" value="<?=$arr_setting->numPost?>" size="16">
                        <span id="daknetcorp_num_show_errmsg"></span><i style="color: #666; font-size: 11px;">Eg. 5</i> 
                        <a class="daknetcorp-tooltip-link" href="JavaScript:void(0);">What does this mean?</a>
                        <p class="des-daknetcorp">The amount of keyworks enough to arrange a post into a category or a tag.</p>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Limit of category/tags of a post</th>
                    <td>
                        <input id="daknetcorp_num_category" name="daknetcorp_num_category" type="text" value="<?=$arr_setting->numCate?>" size="16">
                        <span id="daknetcorp_num_category_errmsg"></span><i style="color: #666; font-size: 11px;">Eg. 100</i> 
                        <a class="daknetcorp-tooltip-link" href="JavaScript:void(0);">What does this mean?</a>
                        <p class="des-daknetcorp">The amount of categories/tags a post can belong to.</p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Limit of post</th>
                    <td>
                        <input id="daknetcorp_post_limit" name="daknetcorp_post_limit" type="text" value="<?=$arr_setting->limitPost?>" size="16">
                        <span id="daknetcorp_post_limit_errmsg"></span><i style="color: #666; font-size: 11px;">Eg. 3</i>
                        <a class="daknetcorp-tooltip-link" href="JavaScript:void(0);">What does this mean?</a>
                        <p class="des-daknetcorp">The amount of posts for each filtering. Set -1 for all posts</p>
                    </td>
                </tr>
            </tbody>
        </table>
	</div>

    <div class="wrap-action">
		<div class="group-action">
			<button type="submit" id='filter_default' name="default" value="start_filter">Default</button>
		</div>

		<div class="group-action">
			<button type="submit" id='filter_save' name="save" value="start_filter">Save</button>
		</div>

		<div class="group-action">
			<button type="submit" id='filter_action_now' name="action-now" value="start_filter">Action Now</button>
		</div>
	</div>
	
	<script type="text/javascript">
		jQuery('#daknetcorp_cache_time_unit').val("<?=$arr_setting->option_time?>");

		// set default setting.
		var is_default = false;
		jQuery('#filter_default').click(function(){
			if(is_default){
				return false;
			}
			is_default = true;
			jQuery('#filter_default').text('processing...').css('background-color', '#ABCABC');

			var time 	= 2;
			var option  = 'minutes';
			var numPost = 3;
			var numCate = 3;
			var numKeyword  = 3;
			var limitPost = -1;
			jQuery.ajax({
		        url 	: ajaxurl,
		        type 	:'post',
		        data 	: {
		            'action'		: 'datc_default_setting',
		            'data_setting'	: {
		            	'time' : time,
		            	'option_time' : option,
		            	'numPost': numPost,
		            	'numCate': numCate,
		            	'limitPost': limitPost
		            }
		        },
		        success	:function(data) {
		        	is_default = false;
					jQuery('#filter_default').text('Default').css('background-color', '#38a16a');

		        	jQuery('#daknetcorp_cache_time').val(time);
		        	jQuery('#daknetcorp_cache_time_unit').val(option);
		        	jQuery('#daknetcorp_num_category').val(numCate);
		        	jQuery('#daknetcorp_num_show').val(numPost);
		        	jQuery('#daknetcorp_post_limit').val(limitPost);
		        	show_message('update_option_daknetcorp');
		        },
		        error 	: function(errorThrown){
		            console.log(errorThrown);
		        }
		    });
			return false;
		});

		jQuery('.daknetcorp-tooltip-link').click(function(){
			jQuery(this).parent().find('p').toggle('500');
		});

		var is_save_option = false
		jQuery('#filter_save').click(function(){
			if(is_save_option){
				return false;
			}
			is_save_option = true;
			jQuery('#filter_save').text('processing...').css('background-color', '#ABCABC');

			var time 	= jQuery('#daknetcorp_cache_time').val();
			var option  = jQuery('#daknetcorp_cache_time_unit').val();
			var numPost = jQuery('#daknetcorp_num_show').val();
			var numCate = jQuery('#daknetcorp_num_category').val();
			var limitPost = jQuery('#daknetcorp_post_limit').val();
			jQuery.ajax({
		        url 	: ajaxurl,
		        type 	:'post',
		        data 	: {
		            'action'		: 'datc_default_setting',
		            'data_setting'	: {
		            	'time' : time,
		            	'option_time' : option,
		            	'numPost': numPost,
		            	'numCate': numCate,
		            	'limitPost': limitPost
		            }
		        },
		        success	:function(data) {
		        	is_save_option =false;
		        	jQuery('#filter_save').text('Save').css('background-color', '#38a16a');
		        	show_message('update_option_daknetcorp');
		        },
		        error 	: function(errorThrown){
		            console.log(errorThrown);
		        }
		    });
			return false;
		});

		onlyNumber('daknetcorp_cache_time');
		onlyNumber('daknetcorp_num_category');
		onlyNumber('daknetcorp_num_show');
		limitNumber('daknetcorp_post_limit', -1,100);
		
		function onlyNumber(elm){
			jQuery("#"+elm).keypress(function (e) {
				if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
					jQuery("#"+elm+'_errmsg').html("Only number 0-9   &nbsp;&nbsp;&nbsp;&nbsp;").show().fadeOut(3000);
					return false;
				}
			});
		}

		function limitNumber(elm, min, max){
			jQuery("#"+elm).focusout(function(){
				var value = jQuery('#'+elm).val();
				
				if(isNaN(parseInt(value))) {
					jQuery("#"+elm+'_errmsg').html("Value invalid ["+min+ ", "+max+"]   &nbsp;&nbsp;&nbsp;&nbsp;").show();
					jQuery("#"+elm).focus();
				} else {
					value = parseInt(value);
					jQuery("#"+elm).val(value);
					
					if(value < min || value > max){
						jQuery("#"+elm+'_errmsg').html("Value invalid ["+min+ ", "+max+"]   &nbsp;&nbsp;&nbsp;&nbsp;").show();
						jQuery("#"+elm).focus();
					} else {
						jQuery("#"+elm+'_errmsg').fadeOut(1000)
					}
				}
			});
		}

		var is_action = false;

		//tt: filter now
		jQuery('#filter_action_now').click(function(){
			if(is_action){
				return false;
			}
			is_action = true;
			jQuery(this).text('processing...').css('background-color','#ABCABC');
			jQuery.ajax({
		        url 	: ajaxurl,
		        type 	:'post',
		        dataType:'json',
		        data 	: {
		            'action'	: 'datc_filter_all'
		        },
		        success	:function(data) {
		        	is_action = false;
		        	jQuery('#filter_action_now').text('Action Now').css('background-color', '#38a16a');
		        	// show_message('filter_now');
		            // jQuery('#res_filter').html(' '+data+' bài viết được sắp xếp');
		            mesage = '';
		            if(parseInt(data.category) > 0){
		            	mesage = data.category +' posts are arranged into categories.';
		            }else{
		            	mesage = 'No post is arranged into categories.';
		            }

		            if(parseInt(data.tag) > 0){
		            	mesage += data.tag +' posts are arranged into tags.';
		            }else{
		            	mesage += 'No post is arranged into tags.';
		            }
					
					toastr.success(mesage);
		        },
		        error 	: function(errorThrown){
		            console.log(errorThrown);
		        }
		    });
		    return false;
		});
	</script>
<?php }

function datc_add_style(){
	wp_enqueue_style( 'category-tab', plugins_url( 'css/', __FILE__ ) . 'style.css'); 
	wp_enqueue_style( 'category-tab-component', plugins_url( 'css/', __FILE__ ) . 'component.css'); 
	wp_enqueue_style( 'category-tab-content', plugins_url( 'css/', __FILE__ ) . 'content.css'); 
	wp_enqueue_style( 'category-tab-toastr', plugins_url( 'css/', __FILE__ ) . 'toastr.css' ); 
	wp_enqueue_style( 'category-tab-jquery-ui.css', plugins_url( 'css/', __FILE__ ) . 'jquery-ui.css'); 
	
	// wp_enqueue_script("jquery");
	wp_enqueue_script(array('jquery','jquery-ui-core','jquery-ui-dialog' ));
	wp_enqueue_script( 'category-tab-toastr', plugins_url( 'js/', __FILE__ ) . 'toastr.js' );    
	wp_enqueue_script( 'category-tab-modernizr', plugins_url( 'js/', __FILE__ ) . 'modernizr.custom.js' );  
	wp_enqueue_script( 'category-tab-classie', plugins_url( 'js/', __FILE__ ) . 'classie.js' );   
	wp_enqueue_script( 'category-tab-uiMorphingButton_fixed', plugins_url( 'js/', __FILE__ ) . 'uiMorphingButton_fixed.js' ); 
}

add_action('admin_init', 'datc_add_style');
// Add option setting
function datc_default_setting() {
	include_once( ABSPATH . 'wp-includes/wp-db.php' );
	global $wpdb; 	 
	$term  = $wpdb->prefix .'options';

  	$data_setting 	= $_POST['data_setting']; 
    if ( isset($data_setting) ) {
    	update_option( DAKNETCORP_AUTO_TAG_CATEGORY_SETTING, json_encode($data_setting)); 
    } 
    die();
}
add_action( 'wp_ajax_datc_default_setting', 'datc_default_setting' );

// Add keyword
function datc_add_item() {
	include_once( ABSPATH . 'wp-includes/wp-db.php' );
	global $wpdb;
	$term  = $wpdb->prefix .'terms';
	$term_taxonomy = $wpdb->prefix.'term_taxonomy';

  	$dataAdd = $_POST['dataAdd'];
    if ( isset($dataAdd) ) {
    	$parent_id 	= $dataAdd['parent_id'];
    	$nameCate 	= $dataAdd['nameCate']; // con.
    	$nameTerm 	= $dataAdd['name_term']; // Mua bán nhà đất.

 		$keyword_taxonomy = $dataAdd['type'].'_keyword';

		$parent_term 	= term_exists( $nameCate, $keyword_taxonomy );  //category
		$parent_term_id = $parent_term['term_id'];
		
		// dont limit keyword add
		$query_ided = "SELECT `term_id` FROM `$term` WHERE `name` = '$nameCate'";
		$ided = $wpdb->get_row($query_ided);
		$ided = ($ided->term_id != 0) ? $ided->term_id : 0;

		$query_insert = "INSERT INTO `$term` (`name`) VALUES ('$nameCate') ";
		$wpdb->query($query_insert);

		$query= "SELECT `term_id` FROM `$term` WHERE `name` = '$nameCate' AND `term_id` <> '$ided'";
		$term_id_new_add = $wpdb->get_row($query);

		$id_ta = $term_id_new_add->term_id;

		$query_insert_taxonomy  = "INSERT INTO `$term_taxonomy` (`term_id`, `taxonomy`, `parent`) ";
		$query_insert_taxonomy .= " VALUES ('$id_ta', '$keyword_taxonomy', '$parent_id') ";
		$wpdb->query($query_insert_taxonomy);

		$data_reponse = array(
			'term_id_new_add'	=> $term_id_new_add->term_id,
			'parent_term'		=> $parent_id,
			'nameCate'			=> $nameCate,
			'type_'				=> $dataAdd['type'],
			'error'				=> 0
		);
		echo json_encode($data_reponse);

    }
    die();
}
add_action( 'wp_ajax_datc_add_item', 'datc_add_item' );

// Edit keyword
function datc_edit_item() {
	include_once( ABSPATH . 'wp-includes/wp-db.php' );
	global $wpdb;
	$term  = $wpdb->prefix .'terms';

  	$dataSend = $_POST['dataSend'];
    if ( isset($dataSend) ) {
    	$term_id 	= $dataSend['term_id'];
    	$nameCate 	= $dataSend['nameCate'];

    	$type = ($dataSend['type'] != '') ? $dataSend['type'] : 'category';
    	$keyword_taxonomy = $type.'_keyword';

    	$parent_term 	= term_exists( $nameCate, $keyword_taxonomy );  //category
		$parent_term_id = $parent_term['term_id'];

		if(is_null($parent_term_id) || $parent_term_id == 0){
			$query = "UPDATE `$term` SET `name` = '$nameCate' WHERE `term_id` = '$term_id'";
    		$wpdb->query($query);

    		if ( ! is_wp_error( $update ) ) {
    			$dataSend['error'] = 0;
			}else{
				$dataSend['error'] = 1;
			}
		}else{
			$dataSend['error'] = 1;
		}
		echo json_encode($dataSend);

    }
    die();
}
add_action( 'wp_ajax_datc_edit_item', 'datc_edit_item' );

// Delete keyword
function datc_delete_item() {
  	$dataSend = $_POST['dataDel'];
    if ( isset($dataSend) ) {
    	$term_id 	= $dataSend['term_id'];
    	$type_del 	= $dataSend['type'] . '_keyword';
    	wp_delete_term( $term_id, $type_del);
    	print_r($term_id) ;
    }
    die();
}
add_action( 'wp_ajax_datc_delete_item', 'datc_delete_item' );

/**
 * Backend: filter all
 */
function datc_filter_all () {
	$result_category = _datc_filter_category();
	$result_tag = _datc_filter_tag();
	echo json_encode(array('category'=> $result_category, 'tag'=> $result_tag));
	exit;
} // end filter all
add_action( 'wp_ajax_datc_filter_all', 'datc_filter_all' ); 
function _datc_filter_category(){
	include_once( ABSPATH . 'wp-includes/wp-db.php' );
    global $wpdb;
	
	$count_filtered_post_for_category = 0;
	
	$term           = $wpdb->prefix .'terms';
    $term_taxonomy  = $wpdb->prefix .'term_taxonomy';
    $posts  		= $wpdb->prefix .'posts';
    $post_meta  	= $wpdb->prefix .'postmeta';
    $term_relationships  = $wpdb->prefix .'term_relationships';
    // $option_tb 		= $wpdb->prefix . 'options';

	$option = get_option(DAKNETCORP_AUTO_TAG_CATEGORY_SETTING);
	if($option){
    	$arrOption = json_decode($option);
    	$limit_of_category_tag_for_a_post  = $arrOption->numCate;
	    $limit_of_keyword_for_filter  = $arrOption->numPost;	
		$post_limit = $arrOption->limitPost;
	}else{
		//TODO: set option and get default values.
		$limit_of_category_tag_for_a_post  = 3;
	    $limit_of_keyword_for_filter  = 3;	
	    $post_limit = -1;
	}

    //just get parent categories only
	$args_list_category = [
		'type'                     => 'post',
		'child_of'                 => 0,
		'parent'                   => '',
		'orderby'                  => 'name',
		'order'                    => 'ASC',
		'hide_empty'               => 0,
		'hierarchical'             => 1,
		'exclude'                  => '',
		'include'                  => '',
		'number'                   => '',
		'taxonomy'                 => 'category',
		'pad_counts'               => false 
	];
	$list_category  = get_categories( $args_list_category );

	$offset = 0; 
	$posts_array_for_fitler = [];

	if ($post_limit != -1) {
		$get_filtered_post  = "select post_id ";
		$get_filtered_post .= "from ". $post_meta;
		$get_filtered_post .=" where meta_key = '".DAKNETCORP_FILTERED_POST."' and meta_value='1' ";
		/**
		 * select post_id from wp_postmeta where meta_key = 'daknetcorp_filtered_post' and meta_value='1' 
		 */
		$results = $wpdb->get_results( $get_filtered_post );
		$exclude_id_list = [];
		foreach ($results as $key => $value) {
			$exclude_id_list[] = $value->post_id;
		}

		$myquery ="	SELECT `p`.`id` AS postid, 
						p.* 
					FROM   $posts AS `p`, 
						$term_relationships AS `r`
					WHERE  `p`.`id` = `r`.`object_id` 
						AND p.post_status = 'publish' 
						AND p.post_type = 'post'
					GROUP  BY p.id 
					HAVING (SELECT Count(t.term_id) 
							FROM   $term_relationships AS r, 
								$term_taxonomy AS ta, 
								$term AS t, 
								$posts 
							WHERE  t.term_id = ta.term_id 
								AND ta.taxonomy = 'category' 
								AND ta.term_taxonomy_id = r.term_taxonomy_id 
								AND $posts.id = r.object_id 
								AND $posts.id = postid) >= $limit_of_category_tag_for_a_post ";
		
		//tt: lay het nhung post thuoc hon 3 category.
		$results = $wpdb->get_results( $myquery );
		foreach ($results as $key => $value) {
			$exclude_id_list[] = $value->postid;
		}
	} else {
		// neu nhu run het post thi khong can xet cai cu nua.
		// cho chay het
		$exclude_id_list = [];
	}

	$args = [
		'posts_per_page'   => $post_limit,
		'offset'           => $offset,
		'category'         => '',
		'category_name'    => '',
		'orderby'          => 'date',
		'order'            => 'DESC',
		'include'          => '',
		'exclude'          => '',
		'meta_key'         => '',
		'meta_value'       => '',
		'post_type'        => 'post',
		'post_mime_type'   => '',
		'post_parent'      => '',
		'author'	   => '',
		'post_status'      => 'publish',
		'suppress_filters' => true ,
		'post__not_in'		=> $exclude_id_list
	];
	
	// lay dc danh sach can fitler
	$posts_array_for_fitler = get_posts( $args );

	foreach ( $posts_array_for_fitler as $key_data_content => $value_content_feed ) { // Content post
        $content_post = mb_strtolower ( $value_content_feed->post_content, 'UTF-8' );
		$id_cat  = array();

		// lay danh sach category de add cho post nay.
		foreach ( $list_category as $key => $item ) { // Category
	        $num 	 = 0;
            $term_id = $item->term_id;
            // $term_taxonomy_id_keyword = $item->term_taxonomy_id;
            
            $list_keyword_query = "	SELECT `term`.`name`, 
									       `term`.`term_id`, 
									       `taxo`.`parent` 
									FROM   $term AS `term` 
									       INNER JOIN $term_taxonomy AS `taxo` 
									               ON `term`.`term_id` = `taxo`.`term_id` 
									WHERE  `taxo`.`parent` = '$term_id' ";
			
			// lay danh sach nhung keyword tu category
			// SELECT `term`.`name`, `term`.`term_id`, `taxo`.`parent` FROM wp_terms AS `term` INNER JOIN wp_term_taxonomy AS `taxo` ON `term`.`term_id` = `taxo`.`term_id` WHERE `taxo`.`parent` = '4' 
			$list_keyword_for_filter = $wpdb->get_results($list_keyword_query);
		
			if(empty($list_keyword_for_filter)){
				// neu khong co keyword nao thi thoat
				break;
			}
			
			// 1 post phai thoa man dieu kien la co het tat ca cac keyword moi dc add.
			if(sizeof($list_keyword_for_filter) < intval($limit_of_keyword_for_filter)){
				// chay loop het cac keyword va tim co trong post hay khong.
				$has_all_keywords_in_post = true;
				foreach ( $list_keyword_for_filter as $findme ) {		// Key word
					$keyword = mb_strtolower ( $findme->name, 'UTF-8' );
					$pos = mb_strpos( $content_post, $keyword );
					
					if($pos === false){
						// co 1 keyword ko ton tai trong post nay.
						// post nay se khong dc add vao danh muc nay.
						// thoat
						//not found, this keyword is not exist in this post
						$has_all_keywords_in_post= false;	
						break;
					}
				}
			
				if($has_all_keywords_in_post){
					$id_cat[] = $term_id;
				}

			}else{
				//case the number of this category's keyword is more then the number in option
				foreach ( $list_keyword_for_filter as $findme ) {		// Key word
					$keyword = mb_strtolower ( $findme->name, 'UTF-8' );
					$pos = mb_strpos( $content_post, $keyword );
					if($pos !== false){
						$num++;
					}

					if( $num >= $limit_of_keyword_for_filter ){
						$id_cat[] = $term_id;
						break;
					}
				}
			}
        } // endfor
		// $id_cats : danh sach category se add cho post nay.
		if(!empty($id_cat)){
		  	$rs = wp_set_post_categories($value_content_feed->ID, $id_cat, true);
		  	if($rs !== false){
		  		//set post meta for this post
				// danh dau fitter
				if ($post_limit != -1) {
					add_post_meta($value_content_feed->ID, DAKNETCORP_FILTERED_POST, '1' , false); 
				}
		  		$count_filtered_post_for_category++;
		  	}
		}
    } //endfor of list category
    return $count_filtered_post_for_category;
}

//TODO: commmenout to focus
function _datc_filter_tag(){
	include_once( ABSPATH . 'wp-includes/wp-db.php' );
    global $wpdb;

    $count_filtered_post_for_tag = 0;

	$term           = $wpdb->prefix .'terms';
    $term_taxonomy  = $wpdb->prefix .'term_taxonomy';
    $posts  		= $wpdb->prefix .'posts';
    $post_meta  	= $wpdb->prefix .'postmeta';
    $term_relationships  = $wpdb->prefix .'term_relationships';
    $option_tb 		= $wpdb->prefix . 'options';

    $option = get_option(DAKNETCORP_AUTO_TAG_CATEGORY_SETTING);
    if($option){
    	$arrOption = json_decode($option);
    	$limit_of_category_tag_for_a_post  = $arrOption->numCate;
	    $limit_of_keyword_for_filter  = $arrOption->numPost;
	    $post_limit = $arrOption->limitPost; //hardcode
	}else{
		//TODO: set option and get default values.
		$limit_of_category_tag_for_a_post  = 3;
	    $limit_of_keyword_for_filter  = 3;
	    $post_limit = -1;
	}

    //just get parent categories only
	$args_list_category = array(
		'type'                     => 'post',
		'child_of'                 => 0,
		'parent'                   => '',
		'orderby'                  => 'name',
		'order'                    => 'ASC',
		'hide_empty'               => 0,
		'hierarchical'             => 1,
		'exclude'                  => '',
		'include'                  => '',
		'number'                   => '',
		'taxonomy'                 => 'post_tag',
		'pad_counts'               => false
	);
	$list_post_tag  = get_categories( $args_list_category );

	$offset = 0;
	$posts_array_for_fitler = [];

	if ($post_limit != -1) {
		$get_filtered_post  = "select post_id ";
		$get_filtered_post .= "from ". $post_meta;
		$get_filtered_post .=" where meta_key = '".DAKNETCORP_FILTERED_TAG."' and meta_value='1' ";
		$results = $wpdb->get_results( $get_filtered_post );

		$exclude_id_list = [];
		foreach ($results as $key => $value) {
			$exclude_id_list[] = $value->post_id;
		}

		$myquery ="	SELECT `p`.`id` AS postid, 
						p.* 
					FROM   $posts AS `p`, 
						$term_relationships AS `r`
					WHERE  `p`.`id` = `r`.`object_id` 
						AND p.post_status = 'publish' 
						AND p.post_type = 'post'
					GROUP  BY p.id 
					HAVING (SELECT Count(t.term_id) 
							FROM   $term_relationships AS r, 
								$term_taxonomy AS ta, 
								$term AS t, 
								$posts 
							WHERE  t.term_id = ta.term_id 
								AND ta.taxonomy = 'post_tag' 
								AND ta.term_taxonomy_id = r.term_taxonomy_id 
								AND $posts.id = r.object_id 
								AND $posts.id = postid) >= $limit_of_category_tag_for_a_post ";

		$results = $wpdb->get_results( $myquery );
		foreach ($results as $key => $value) {
			$exclude_id_list[] = $value->postid;
		}
	} else {
		// no limit. RUN ALL
		$exclude_id_list = [];
	}
	
	$args = array(
		'posts_per_page'	=> $post_limit,
		'offset'           	=> $offset,
		'category'         	=> '',
		'category_name'    	=> '',
		'orderby'          	=> 'date',
		'order'            	=> 'DESC',
		'include'          	=> '',
		'exclude'          	=> '',
		'meta_key'         	=> '',
		'meta_value'       	=> '',
		'post_type'        	=> 'post',
		'post_mime_type'   	=> '',
		'post_parent'      	=> '',
		'author'	   		=> '',
		'post_status'      	=> 'publish',
		'suppress_filters' 	=> true ,
		'post__not_in'	   	=> $exclude_id_list
	);

	$posts_array_for_fitler = get_posts( $args );
	foreach ( $posts_array_for_fitler as $key_data_content => $value_content_feed ) { // Content post
        $content_post = mb_strtolower ( $value_content_feed->post_content, 'UTF-8' );
        $id_cat  = array();
        $tag_name  = '';
		foreach ( $list_post_tag as $key => $item ) { // Tag
	        $num 	 = 0;
            $term_id = $item->term_id;
            $term_name = $item->name;
            $term_taxonomy_id_keyword = $item->term_taxonomy_id;

            $list_keyword_query = "	SELECT `term`.`name`, 
									       `term`.`term_id`, 
									       `taxo`.`parent` 
									FROM   $term AS `term` 
									       INNER JOIN $term_taxonomy AS `taxo` 
									               ON `term`.`term_id` = `taxo`.`term_id` 
									WHERE  `taxo`.`parent` = '$term_id' ";

			$arttribute_item = $wpdb->get_results($list_keyword_query);
			if(!empty($arttribute_item)){
				if(sizeof($arttribute_item) < intval($limit_of_keyword_for_filter)){
					$is_enough_keyword = true;
					foreach ( $arttribute_item as $findme ) {		// Key word
	                    $keyword = mb_strtolower ( $findme->name, 'UTF-8' );
	        			$pos = mb_strpos( $content_post, $keyword );

	        			if($pos === false){
	                    	//oh, this keyword is exist in this post
	                    	$is_enough_keyword= false;	
	                    	break;
	                    } 
					}

					if($is_enough_keyword){
			        	$tag_name = $term_name . ',';
					}
				}else{
					//case the number of this category's keyword is more then the number in option
					foreach ( $arttribute_item as $findme ) {		// Key word
	                    $keyword = mb_strtolower ( $findme->name, 'UTF-8' );
	                    $pos = mb_strpos( $content_post, $keyword );
	                    if($pos !== false){
	                    	$num++;
	                    }

	                    if( $num >= $limit_of_keyword_for_filter ){
	                        $tag_name = $term_name . ',';
	                        break;
	                    }
	                }
				}
            }//end if checking keyword exist
        } // endfor

        if($tag_name != ''){
        	$tag_name = "'".rtrim($tag_name, ',') ."'";
		  	$rs = wp_set_post_tags($value_content_feed->ID, $tag_name, true);
		  	if($rs !== false){
				  //set post meta for this post
				if ($post_limit != -1) {
					add_post_meta($value_content_feed->ID, DAKNETCORP_FILTERED_TAG, '1' , false);
				}
		  		
		  		$count_filtered_post_for_tag++;
		  	}
		}
    } //endfor of list category
    return $count_filtered_post_for_tag;
}

///////////////CRONJOB////////////////////
function datc_cron_intervals( $array ){
	include_once( ABSPATH . 'wp-includes/wp-db.php' );
    global $wpdb;
    $option_tb 	= $wpdb->prefix . 'options';

    $option    	= $wpdb->get_row("SELECT `option_value` FROM `$option_tb` WHERE `option_name` = '".DAKNETCORP_AUTO_TAG_CATEGORY_SETTING."'");
    $arrOption 	= json_decode($option->option_value);
	$type_time 	= $arrOption->option_time;

    switch ($type_time) {
    	case 'minutes':
    		$run_time = $arrOption->time* 60;
    		break;
    	case 'hours':
    		$run_time = $arrOption->time * 60 * 60 ; 
    		break;
    	case 'days':
    		$run_time = $arrOption->time * 60 * 60 * 24; 
    		break;
    	default:
    		# code...
    		break;
    }

    $array['daknetcorp_auto_tag_category_cron'] = array(
        'interval' => $run_time,
        'display' => 'Daknetcorp Auto Tag Category Cron'
    );
    return $array;
}
add_filter('cron_schedules', 'datc_cron_intervals');
add_action('datc_cronjob_fillter_hook', 'datc_cronjob_fillter_exec');


function datc_cronjob_fillter_exec() {
	$result_category = _datc_filter_category();
	$result_tag = _datc_filter_tag();
}

function datc_deactivation_cronjob() {
	$timestamp = wp_next_scheduled( 'datc_cronjob_fillter_hook' );
	wp_unschedule_event($timestamp, 'datc_cronjob_fillter_hook' );
}

function datc_activation_cronjob() {
	if( !wp_next_scheduled( 'datc_cronjob_fillter_hook' ) ) {
	   wp_schedule_event( time(), 'daknetcorp_auto_tag_category_cron', 'datc_cronjob_fillter_hook' );
	}
}