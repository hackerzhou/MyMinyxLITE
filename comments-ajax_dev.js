/**
 * WordPress jQuery-Ajax-Comments v1.3 by Willin Kan.
 * URI: http://kan.willin.org/?p=1271
 */
var i = 0,
got = -1,
len = document.getElementsByTagName('script').length;
while (i <= len && got == -1) {
  var js_url = document.getElementsByTagName('script')[i].src,
  got = js_url.indexOf('comments-ajax.js');
  i++;
}
var edit_mode = '1',
ajax_php_url = js_url.replace('-ajax.js', '-ajax.php'),
wp_url = js_url.substr(0, js_url.indexOf('wp-content')),
pic_sb = wp_url + 'wp-admin/images/wpspin_light.gif',
pic_no = wp_url + 'wp-admin/images/no.png',
pic_ys = wp_url + 'wp-admin/images/yes.png',
txt1 = '<div id="loading"><img width="12" src="' + pic_sb + '" style="vertical-align:middle;" alt=""/> 正在提交，请稍候...</div>',
txt2 = '<div id="error">#</div>',
txt3 = '"><img src="' + pic_ys + '" style="vertical-align:middle;" alt=""/> 提交成功',
edt1 = ', 刷新页面之前可以<a rel="nofollow" class="comment-reply-link" href="#edit" onclick=\'return addComment.moveForm("',
edt2 = ')\'>编辑评论</a>',
cancel_edit = '取消编辑',
edit,
num = 1,
comm_array = [];
comm_array.push('');

function getCookie(c_name) {
  if (document.cookie.length > 0) {
    c_start = document.cookie.indexOf(c_name + "=")
    if (c_start != -1) {
      c_start = c_start + c_name.length + 1
      c_end = document.cookie.indexOf(";", c_start)
      if (c_end == -1) c_end = document.cookie.length
      return unescape(document.cookie.substring(c_start, c_end))
    }
  }
  return ""
}

function onCommSubmit() {
  try {
    var v = parseInt(getCookie('comm_verify'));
    var c = (v & 0x55555555) + ((v >> 1) & 0x55555555);
    c = (c & 0x33333333) + ((c >> 2) & 0x33333333);
    c = (c & 0x0F0F0F0F) + ((c >> 4) & 0x0F0F0F0F);
    c = (c & 0x00FF00FF) + ((c >> 8) & 0x00FF00FF);
    c = (c & 0x0000FFFF) + ((c >> 16) & 0x0000FFFF);
    var ans = document.getElementById("comm_verify_ans");
    if (ans) {
    	ans.value = c;
    } else {
    	$('textarea.comm_input').after('<input type="hidden" id="comm_verify_ans" name="comm_verify_ans" value="' + c + '"/>');
    }
  } catch(e) {}
}

jQuery(document).ready(function($) {
  $comments = $('#comments-title h3');
  $cancel = $('#cancel-comment-reply-link');
  cancel_text = $cancel.text();
  $submit = $('#commentform #submit');
  $submit.attr('disabled', false);
  $('textarea.comm_input').after(txt1 + txt2);
  $('#loading').hide();
  $('#error').hide();
  $body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body');

  /** submit */
  $('#commentform').submit(function() {
    $('#loading').slideDown();

    $submit.attr('disabled', true).fadeTo('slow', 0.5);
    if (edit) {
      $('textarea.comm_input').after('<input type="text" name="edit_id" id="edit_id" value="' + edit + '" style="display:none;" />');
    }

    /** Ajax */
    $.ajax({
      url: ajax_php_url,
      data: $(this).serialize(),
      type: $(this).attr('method'),

      error: function(request) {
        $('#loading').slideUp();
        $('#error').slideDown().html('<img width="12" src="' + pic_no + '" style="vertical-align:middle;" alt=""/> ' + request.responseText);
        setTimeout(function() {
          $submit.attr('disabled', false).fadeTo('slow', 1);
          $('#error').slideUp();
        },
        3000);
      },

      success: function(data) {
        $('#loading').hide();
        comm_array.push($('textarea.comm_input').val());
        $('textarea').each(function() {
          this.value = ''
        });
        var t = addComment;
        var cancel = t.I('cancel-comment-reply-link'),
        temp = t.I('wp-temp-form-div'),
        respond = t.I(t.respondId),
        post = t.I('hackerzhou_article_id').value,
        parent = t.I('hackerzhou_com_parent_id').value;
        // comments
        if (!edit && $comments.length) {
          n = parseInt($comments.text().match(/\d+/));
          $comments.text($comments.text().replace(n, n + 1));
        }

        // show comment
        new_htm = '" id="new_comm_' + num + '"></';
        new_htm = (parent == '0') ? ('\n<ol style="clear:both;padding-top:0px;" class="commentlist' + new_htm + 'ol>') : ('\n<ul class="children' + new_htm + 'ul>');

        ok_htm = '\n<span id="success_' + num + txt3;
        if (edit_mode == '1') {
          div_ = (document.body.innerHTML.indexOf('div-comment-') == -1) ? '': ((document.body.innerHTML.indexOf('li-comment-') == -1) ? 'div-': '');
          ok_htm = ok_htm.concat(edt1, div_, 'comment-', parent, '", "', parent, '", "respond", "', post, '", ', num, edt2);
        }
        ok_htm += '</span><span></span>\n';
        if (parent == '0') {
          if ($(".navigation").length > 0) {
            $('.navigation').before(new_htm);
          } else {
        	$('#respond').before(new_htm);
          }
        } else {
          $('#comment-' + parent).after(new_htm);
        }

        $('#new_comm_' + num).hide().append(data);
        $('#new_comm_' + num + ' li').append(ok_htm);
        $('#new_comm_' + num).fadeIn(4000);
        if ($('#new_comm_' + num).offset() != null) {
          $body.animate({
            scrollTop: $('#new_comm_' + num).offset().top - 200
          },
          900);
        }
        countdown();
        num++;
        edit = '';
        $('*').remove('#edit_id');
        cancel.style.display = 'none';
        cancel.onclick = null;
        t.I('hackerzhou_com_parent_id').value = '0';
        if (temp && respond) {
          temp.parentNode.insertBefore(respond, temp);
          temp.parentNode.removeChild(temp)
        }
      }
    }); // end Ajax
    return false;
  }); // end submit
  /** comment-reply.dev.js */
  addComment = {
    moveForm: function(commId, parentId, respondId, postId, num) {
      var t = this,
      div, comm = t.I(commId),
      respond = t.I(respondId),
      cancel = t.I('cancel-comment-reply-link'),
      parent = t.I('hackerzhou_com_parent_id'),
      post = t.I('hackerzhou_article_id');
      if (edit) exit_prev_edit();
      if (num) {
        $('textarea.comm_input').each(function() {
          this.value = comm_array[num]
        });
        edit = t.I('new_comm_' + num).innerHTML.match(/(comment-)(\d+)/)[2];
        $new_sucs = $('#success_' + num);
        $new_sucs.hide();
        $new_comm = $('#new_comm_' + num);
        $new_comm.hide();
        $cancel.text(cancel_edit);
      } else {
        $cancel.text(cancel_text);
      }

      t.respondId = respondId;
      postId = postId || false;

      if (!t.I('wp-temp-form-div')) {
        div = document.createElement('div');
        div.id = 'wp-temp-form-div';
        div.style.display = 'none';
        if (parent == '0') {
          respond.parentNode.insertBefore(div, $('.navigation').get());
        } else {
          respond.parentNode.insertBefore(div, $('#comment-' + parent).get().nextSibling);
        }
      }

      ! comm ? (
      temp = t.I('wp-temp-form-div'), t.I('hackerzhou_com_parent_id').value = '0', temp.parentNode.insertBefore(respond, temp), temp.parentNode.removeChild(temp)) : comm.parentNode.insertBefore(respond, comm.nextSibling);
      respond.style.opacity = 0;
      $body.animate({
        scrollTop: $('#respond').offset().top - 100
      },
      500);
      $('#respond').animate({
        opacity: 1
      },
      400);

      if (post && postId) post.value = postId;
      parent.value = parentId;
      cancel.style.display = 'inline-block';

      cancel.onclick = function() {
        if (edit) exit_prev_edit();
        var t = addComment,
        temp = t.I('wp-temp-form-div'),
        respond = t.I(t.respondId);

        t.I('hackerzhou_com_parent_id').value = '0';
        if (temp && respond) {
          $('#respond').animate({
            opacity: 0
          },
          400,
          function() {
            temp.parentNode.insertBefore(respond, temp);
            temp.parentNode.removeChild(temp);
            $('#respond').animate({
              opacity: 1
            },
            400);
          });
        }
        this.style.display = 'none';
        this.onclick = null;
        return false;
      };

      try {
        if ($("input.input_0").length > 0) {
          var hasName = $("input.input_0").val().length > 0;
          var hasEmail = $("input.input_1").val().length > 0;
          if (!hasName) {
            $("input.input_0").focus();
          } else if (!hasEmail) {
            $("input.input_1").focus();
          } else {
            $("textarea.comm_input").focus();
          }
        } else {
          $("textarea.comm_input").focus();
        }
      }
      catch(e) {}

      return false;
    },

    I: function(e) {
      return document.getElementById(e);
    }
  };
  // end addComment
  function exit_prev_edit() {
    $new_comm.show();
    $new_sucs.show();
    $('textarea').each(function() {
      this.value = ''
    });
    edit = '';
  }

  var wait = 15,
  submit_val = $submit.val();
  function countdown() {
    if (wait > 0) {
      $submit.val("请等待" + wait + "秒");
      wait--;
      setTimeout(countdown, 1000);
    } else {
      $submit.val(submit_val).attr('disabled', false).fadeTo('slow', 1);
      wait = 15;
    }
  }

});
// end jQ