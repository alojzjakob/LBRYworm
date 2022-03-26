function lbryGetCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i <ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

String.prototype.stripSlashes = function(){
    return this.replace(/\\(.)/mg, "$1");
}

jQuery.noConflict();
jQuery( document ).ready(function( $ ) {
  // Code that uses jQuery's $ can follow here.
  
  // LIGHT DARK THEME SWITCH
  $(function(){
      //https://github.com/ForEvolve/bootstrap-dark
      $(".lbry-light-dark").each(function(){
          $(this).on('click',function(e){
              e.preventDefault();
              
              if(lbryGetCookie('lbry_dark')==1){
                  document.cookie = "lbry_dark=0; path=/";
                  $("body").removeClass('dark');
                  //$("body").removeClass('bootstrap-dark');
              }else{
                  document.cookie = "lbry_dark=1; path=/";
                  $("body").addClass('dark');
                  //$("body").addClass('bootstrap-dark');
              }
              
              
              
          });
      });
          
  });

  
  
  // BEGIN SEARCH
  $(function(){
      
      function focus_search_form(){
          var input=$("#search_query");
          input.focus();
          var tmpStr = input.val();
          input.val('');
          input.val(tmpStr);
      }

      focus_search_form();
      
      var allow_infinite_scroll=false;
      var allow_infinite_scroll=false;
      
      var item_from=0;
      var items_per_page=20;
      var item_count=items_per_page;
      
      
      $("#search_form").on('submit',function(e){
          allow_infinite_scroll=false;
          item_from=0;
          $("#search_form button .looking_glass").hide();
          $("#search_form button .search_loading").show();
          $("#nothing_found").stop().fadeOut(300,function(){
            $("#nothing_found_error").stop().html('').hide();
          });
          
          
          var form_data=$("#search_form").serialize();
          
          //console.log(form_data);
          
          $("#search_results").fadeOut(500);
          $("#homepage_hero").fadeOut(300,function(){
            $("#infinite_scroll_loader").fadeIn(200);
          });
          
          
          e.preventDefault();
          $.ajax({
                    type: "POST",
                    url: lbryworm_ajax_url+'&method=search',
                    data: form_data+'&from='+item_from+'&count='+item_count,
                    success: function(response){
                      $("#search_form button .looking_glass").show();
                      $("#search_form button .search_loading").hide();
                      $("#infinite_scroll_loader").hide();
                      $("#search_results").html('');
                      item_from=item_from+item_count;
                      if(process_response(response)==true){
                        allow_infinite_scroll=true;
                      }else{
                        allow_infinite_scroll=false;
                      }
                      $("#infinite_scroll_loader").hide(300,function(){
                        $("#search_results").fadeIn(300);
                        //focus_search_form();
                        $("#search_query").blur();
                      });
                      
                    },
                    //dataType: dataType
                  });
          return false;
        
      });
      
      function handle_infinite_scroll(){
        allow_infinite_scroll=false;
        $("#infinite_scroll_loader").fadeIn(200);
        var form_data=$("#search_form").serialize();
        $.ajax({
                    type: "POST",
                    url: lbryworm_ajax_url+'&method=search',
                    data: form_data+'&from='+item_from+'&count='+item_count,
                    success: function(response){
//                       $("#search_form button .looking_glass").show();
//                       $("#search_form button .search_loading").hide();
                      item_from=item_from+item_count;
                      if(process_response(response)==true){
                        allow_infinite_scroll=true;
                      }else{
                        allow_infinite_scroll=false;
                      }
                      $("#infinite_scroll_loader").hide();
                    },
                    //dataType: dataType
                  });
      }
      
      $(window).scroll(function () {
          // End of the document reached?
          if ($(document).height() - $(this).height() <= $(this).scrollTop()+200 && allow_infinite_scroll) {
              handle_infinite_scroll()
          }
      }); 
      
      var infiniteScrollInterval=setInterval(function(){
          if ($(document).height() - $(this).height() == $(this).scrollTop()+200 && allow_infinite_scroll) {
              handle_infinite_scroll()
          }
      },1000);
      
      
      function process_response(response){
        var raw_response=response;
        if(response.error!=="undefined" && response.error!==null){
          allow_infinite_scroll=false;
          //console.log('no results, no infinite scroll');
          $("#nothing_found_term").html($("#search_query").val());
          $("#nothing_found_error").stop().html(response.error).fadeIn(100);
          $("#nothing_found").stop().fadeIn(200);
          return false;
        }
        if(response.data===null){
          allow_infinite_scroll=false;
          //console.log('no results, no infinite scroll');
          $("#nothing_found_term").html($("#search_query").val());
          $("#nothing_found").stop().fadeIn(200);
          return false;
        }
        if(response.data.length==0 && item_from==items_per_page){
          allow_infinite_scroll=false;
          $("#nothing_found_term").html($("#search_query").val());
          $("#nothing_found").stop().fadeIn(200);
          return false;
        }
        if(response.data.length==0){
          allow_infinite_scroll=false;
          //console.log('no more results, stopping infinite scroll');
          return false;
        }
        response.data.forEach(function(r){
          var thumbnail_css=` background-image:url(${lbryworm_site_url}/wp-content/plugins/lbryworm/css/lbryworm-placeholder.png);
                              background-color:#296c57;
                              background-size:contain!important;
                              `;
          if(r.thumbnail_url!==''){
            thumbnail_css=`background-image:url(${r.thumbnail_url});background-position:top center;`;
          }
          
          
          var content_type='';
          var extension='';
          
          if(r.content_type==='application/pdf'){
            content_type='pdf';
            extension='pdf';
          }
          
          if(r.content_type==='application/epub+zip'){
            content_type='epub';
            extension='epub';
          }
          if(r.content_type==='application/vnd.comicbook-rar'){
            content_type='comicbook';
            extension='cbr';
          }
          if(r.content_type==='application/vnd.comicbook+zip'){
            content_type='comicbook';
            extension='cbz';
          }
          
          //if(typeof raw_response.channels[r.publisher_id]!=="undefined"){
          var html=`<div class="search_result_item">
                        <div class="search_result_item_sidebar">
                          <div class="search_result_item_thumb" style="${thumbnail_css}">
                          </div>
                          <div class="external_links">
                            <a href="lbry://${r.name}#${r.claim_id}" target="_blank" title="Open via LBRY app">
                                <img class="external" src="/wp-content/plugins/lbryworm/css/open_on_lbrytv.png">
                            </a>
                            <a href="https://odysee.com/${r.name}:${r.claim_id}" target="_blank" title="Open on Odysee">
                                <img class="external" src="/wp-content/plugins/lbryworm/css/open_on_odysee.png">
                            </a>
                          </div>
                          
                        </div>
                        <div class="search_result_item_link">
                            <span class="filetype ${content_type}">${content_type}</span>
                            <a href="${lbryworm_site_url}/wp-content/plugins/lbryworm/views/modal/book_add_room.php?claim_id=${r.claim_id}" rel="modal:open" class="add_to_library" title="Add to library">
                              <i class="fa fa-plus"></i> <i class="fa fa-book"></i>
                            </a>
                            <a href="javascript:void(0)" id="pending_${r.claim_id}" class="pending_link direct_link" data-claim_id="${r.claim_id}" data-params="lbry://${r.name}:${r.claim_id}">
                                ${r.title} <span class="pending_loading"><i class="fas fa-spinner fa-pulse"></i><!-- loading direct link--></span>
                            </a>
                            <a href="javascript:void(0)" id="download_${r.claim_id}" class="download_link" style="display:none;" data-claim_id="${r.claim_id}" data-params="lbry://${r.name}:${r.claim_id}" data-name="${r.title}.${extension}">
                                <i class="fa fa-download"></i></span>
                            </a>
                        </div>
                        <div class="search_result_item_description">
                            ${r.description}
                        </div>
                        <div class="clearfix"></div>
                    </div>`;
          //}
          
          $("#search_results").append(html);

        });
        
        var get_urls_req_data=[];
        $(".pending_link").each(function(){
          var claim_id=$(this).data('claim_id');
          //$lbryURI='lbry://'.$r['name'].':'.$r['claim_id'];
          var params=$(this).data('params');
          get_urls_req_data.push({lbry_url:params,claim_id:claim_id});
        });
        
        $.ajax({
                  type: "POST",
                  url: lbryworm_ajax_url+'&method=get_stream_urls',
                  data: {data:JSON.stringify(get_urls_req_data)},
                  success: function(response){
                    response.forEach(function(r){
                      $("#pending_"+r.claim_id).attr('href',r.streaming_url);
                      $("#pending_"+r.claim_id).attr('target','_blank');
                      $("#pending_"+r.claim_id).removeClass('pending_link');
                      $("#pending_"+r.claim_id+" .pending_loading").fadeOut(100);
//                       $("#download_"+r.claim_id).fadeIn().on('click',function(e){
//                         e.preventDefault();
//                         //download_book(r.streaming_url,$(this).data('name'));
//                         saveAs(r.streaming_url,$(this).data('name'));
//                       });
                      
                    });
                  },
                  //dataType: dataType
                });
        return true;
      }
      
      
  });
  
  // END SEARCH
  
  // BEGIN LIBRARY
  
  $(function(){
    
    render_bookshelf_breaks();
    
    $( window ).resize(function() {
      //$( "body" ).prepend( "<div>" + $( window ).width() + "</div>" );
      render_bookshelf_breaks();
    });
    
  });
  
  
  // END LIBRARY
  
  
  
  
});


function render_bookshelf_breaks(){
  jQuery('.bookshelf_break').remove();
  //var width = jQuery(window).width();
  var width = jQuery(".wp-site-blocks").width();
  var win_width = jQuery(window).width();
  if(width>900) width=900;
  if(win_width>1100) width=1000;
  //console.log(width);
  var book_num=0;
  var book_i=0;
  var book_count=jQuery('.book').length;
  //console.log(book_count);
  if(book_count==0){
    jQuery("#books").append('<div class="bookshelf_break" style="margin-top:150px;"></div>');
  }
  jQuery('.book').each(function(){
    book_num++;
    book_i++;
    if(((book_num*50)+200)>=width){
      jQuery(this).after('<div class="bookshelf_break"></div>');
      book_num=0;
    }
    if(book_i==book_count && book_num!==0){
      jQuery(this).after('<div class="bookshelf_break"></div>');
    }
  });
}

//https://github.com/kylefox/jquery-modal

function add_room_handler(){
  jQuery( document ).ready(function( $ ) {
    $("#add_room_form").on('submit',function(e){
      e.preventDefault();
      var form_data=$("#add_room_form").serialize();
      $.ajax({
                    type: "POST",
                    url: lbryworm_ajax_url+'&method=add_room',
                    data: form_data,
                    success: function(response){
                      if(!response.error){
                        
                        var room_style='';
                        var bg_image=$('#bg_image').val();
                        if(bg_image!==''){
                            room_style+='background-image:url('+lbryworm_site_url+'/wp-content/plugins/lbryworm/images/room_wallpapers/'+bg_image+'.jpg);background-position:center center;';
                            if(bg_image.indexOf('-tile')>0){
                                room_style+='background-repeat:repeat;';
                            }else{
                                room_style+='background-repeat:no-repeat;background-size:cover;';
                            }
                        }
                        
                        $("#rooms").append(`<div class="library_item" id="room_${response.data.id}" style="display:none;${room_style}">
                                              <a class="library_item_title" href="?room=${response.data.id}">
                                                ${response.data.room_name.stripSlashes()} 
                                              </a>
                                              <div class="library_controls f-right">
                                                <a href="${lbryworm_site_url}/wp-content/plugins/lbryworm/views/modal/room_remove.php?id=${response.data.id}" rel="modal:open"><i class="fa fa-trash"></i></a>
                                              </div>
                                              <div class="clearfix"></div>
                                            </div>`);
                        $("#room_"+response.data.id).fadeIn();
                        $.modal.close();
                      }else{
                        $("#error_message").html(response.error_message).fadeIn();
                      }
                    },
                  });
      return false;
    });
  });
}

function remove_room_handler(){
  jQuery( document ).ready(function( $ ) {
    $("#remove_room_form").on('submit',function(e){
      e.preventDefault();
      var form_data=$("#remove_room_form").serialize();
      var room_id=$("#remove_room_form").data('room_id');
      $.ajax({
                    type: "POST",
                    url: lbryworm_ajax_url+'&method=remove_room',
                    data: form_data,
                    success: function(response){
                      $.modal.close();
                      $("#room_"+room_id).fadeOut(function(){
                        $(this).remove();
                      });
                    },
                  });
      return false;
    });
  });
}

function add_shelf_handler(){
  jQuery( document ).ready(function( $ ) {
    $("#add_shelf_form").on('submit',function(e){
      e.preventDefault();
      var form_data=$("#add_shelf_form").serialize();
      $.ajax({
                    type: "POST",
                    url: lbryworm_ajax_url+'&method=add_shelf',
                    data: form_data,
                    success: function(response){
                      if(!response.error){
                        
                        var shelf_style='';
                        var bg_image=$('#bg_image').val();
                        //console.log(bg_image);
                        if(bg_image!==''){
                            shelf_style+='background-image:url('+lbryworm_site_url+'/wp-content/plugins/lbryworm/images/shelf_textures/'+bg_image+'.jpg);background-position:center center;background-repeat:repeat;';
                        }else{
                            shelf_style+='background-image:url('+lbryworm_site_url+'/wp-content/plugins/lbryworm/images/shelf_textures/wood-texture-1.jpg);background-position:center center;background-repeat:repeat;';
                        }
                        //console.log(shelf_style);
                        
                        $("#shelves").append(`<div class="library_item" id="shelf_${response.data.id}" style="display:none;${shelf_style}">
                                              <a class="library_item_title" href="?shelf=${response.data.id}">
                                                ${response.data.shelf_name.stripSlashes()} 
                                              </a>
                                              <div class="library_controls f-right">
                                                <a href="${lbryworm_site_url}/wp-content/plugins/lbryworm/views/modal/shelf_remove.php?id=${response.data.id}" rel="modal:open"><i class="fa fa-trash"></i></a>
                                              </div>
                                              <div class="clearfix"></div>
                                            </div>`);
                        $("#shelf_"+response.data.id).fadeIn();
                        $.modal.close();
                      }else{
                        $("#error_message").html(response.error_message).fadeIn();
                      }
                    },
                  });
      return false;
    });
  });
}

function remove_shelf_handler(){
  jQuery( document ).ready(function( $ ) {
    $("#remove_shelf_form").on('submit',function(e){
      e.preventDefault();
      var form_data=$("#remove_shelf_form").serialize();
      var shelf_id=$("#remove_shelf_form").data('shelf_id');
      $.ajax({
                    type: "POST",
                    url: lbryworm_ajax_url+'&method=remove_shelf',
                    data: form_data,
                    success: function(response){
                      $.modal.close();
                      $("#shelf_"+shelf_id).fadeOut(function(){
                        $(this).remove();
                      });
                    },
                  });
      return false;
    });
  });
}

function add_book_handler(){
  jQuery( document ).ready(function( $ ) {
    $("#add_book_form").on('submit',function(e){
      e.preventDefault();
      var form_data=$("#add_book_form").serialize();
      $.ajax({
                    type: "POST",
                    url: lbryworm_ajax_url+'&method=add_book_to_shelf',
                    data: form_data,
                    success: function(response){
                      if(!response.error){
                        alert('Book added to the shelf!');
                        $.modal.close();
                      }else{
                        //$("#error_message").html(response.error_message).fadeIn();
                      }
                    },
                  });
      return false;
    });
  });
}

function remove_book_handler(){
  jQuery( document ).ready(function( $ ) {
    $("#remove_book_form").on('submit',function(e){
      e.preventDefault();
      var form_data=$("#remove_book_form").serialize();
      var book_id=$("#remove_book_form").data('book_id');
      $.ajax({
                    type: "POST",
                    url: lbryworm_ajax_url+'&method=remove_book',
                    data: form_data,
                    success: function(response){
                      $.modal.close();
                      $("#book_"+book_id).fadeOut(function(){
                        $(this).remove();
                        render_bookshelf_breaks();
                      });
                    },
                  });
      return false;
    });
  });
}



function download_book(source,name){
    //const fileName = source.split('/').pop();
    var fileName=name;
    var el = document.createElement("a");
    el.setAttribute("href", source);
    el.setAttribute("target", '_blank');
    el.setAttribute("download", fileName);
    console.log(el);
    document.body.appendChild(el);
    el.click();
    el.remove();
}











