$(function() {
    var INDEX = 0;
    $("#chat-submit").click(function(e) {
        e.preventDefault();
        var msg = $("#chat-input").val();
        if (msg.trim() == '') {
            return false;
        }
        var buttons = [{
                name: 'Existing User',
                value: 'existing'
            },
            {
                name: 'New User',
                value: 'new'
            }
        ];

    })

    // function generate_message(msg, type) {
    //   INDEX++;
    //   var str="";
    //   str += "<div id='cm-msg-"+INDEX+"' class=\"chat-msg "+type+"\">";
    //   str += "          <span class=\"msg-avatar\">";
    //   str += "           ";
    //   str += "          <\/span>";
    //   str += "          <div class=\"cm-msg-text\">";
    //   str += msg;
    //   str += "          <\/div>";
    //   str += "        <\/div>";
    //   $(".chat-logs").append(str);
    //   $("#cm-msg-"+INDEX).hide().fadeIn(300);
    //   if(type == 'self'){
    //    $("#chat-input").val('');
    //   }
    //   $(".chat-logs").stop().animate({ scrollTop: $(".chat-logs")[0].scrollHeight}, 1000);
    // }

    function generate_button_message(msg, buttons) {
        /* Buttons should be object array
          [
            {
              name: 'Existing User',
              value: 'existing'
            },
            {
              name: 'New User',
              value: 'new'
            }
          ]
        */
        // INDEX++;
        // var btn_obj = buttons.map(function(button) {
        //    return  "              <li class=\"button\"><a href=\"javascript:;\" class=\"btn btn-primary chat-btn\" chat-value=\""+button.value+"\">"+button.name+"<\/a><\/li>";
        // }).join('');
        // var str="";
        // str += "<div id='cm-msg-"+INDEX+"' class=\"chat-msg user\">";
        // str += "          <span class=\"msg-avatar\">";
        // str += "           ";
        // str += "          <\/span>";
        // str += "          <div class=\"cm-msg-text\">";
        // str += msg;
        // str += "          <\/div>";
        // str += "          <div class=\"cm-msg-button\">";
        // str += "            <ul>";
        // str += btn_obj;
        // str += "            <\/ul>";
        // str += "          <\/div>";
        // str += "        <\/div>";
        // $(".chat-logs").append(str);
        // $("#cm-msg-"+INDEX).hide().fadeIn(300);
        // $(".chat-logs").stop().animate({ scrollTop: $(".chat-logs")[0].scrollHeight}, 1000);
        // $("#chat-input").attr("disabled", true);
    }

    $(document).delegate(".chat-btn", "click", function() {
        var value = $(this).attr("chat-value");
        var name = $(this).html();
        $("#chat-input").attr("disabled", false);
    })

    $(".chat-btn").click(function(e) {
        $(".chat-btn").toggle('scale');
        $(".chat-box").toggle('scale');
        $("#div-fab").css('display', 'none');
        $("#container-chat").addClass('show');
        if ($(this).hasClass('showMsjAdmin')) {
            $("#msj-duda").css("display", "block");
            $(".btnPreinscripcion").css('margin-left', '12px');
        }
        document.getElementById('chat-box').scrollTo(0, 99999);
    })

    $(".chat-box-toggle").click(function() {
        $(".chat-btn").toggle('scale');
        $(".chat-box").toggle('scale');
        $("#div-fab").css('display', 'block');
        $("#msj-duda").css("display", "none");
        $("#container-chat").removeClass('show');
        $(".btnPreinscripcion").css('margin-left', '0px');
    })

})