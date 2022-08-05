<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->
<!-- <script src="js/socket.io.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/3.0.3/socket.io.js" integrity="sha512-Jr0UIR/Q8MUX+93zjDOhuDUKLqJZObtwpkLJQcR9qMaLgL0thet39IORuavUaZFkZ8a4ktrUsKPM9mf5LWMduA==" crossorigin="anonymous"></script>

<html>
<head>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" type="text/css" rel="stylesheet"/>

<script>
  var server = "http://localhost:3000";
  var io = io(server);
  var myName = "";
  var otherPersonName = "";

  function enterName() {
    myName = document.getElementById("name").value;
    io.emit("user_connected", myName);

    alert("You are connected");
    return false;
  }

  function sendMessage() {
    var message = document.getElementById("message").value;
    io.emit("send_message", {
      "sender": myName,
      "receiver": otherPersonName,
      "message": message
    });

    var html = "";
    html += '<div class="outgoing_msg">';
      html += '<div class="sent_msg">';
        html += '<p>' + message + '</p>';
      html += '</div>';
    html += '</div>';
    document.getElementById("messages").innerHTML += html;

    return false;
  }

  io.on("message_received", function (data) {

    var html = "";
    html += '<div class="incoming_msg">';
      html += '<div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>';
      html += '<div class="received_msg">';
        html += '<div class="received_withd_msg">';
          html += '<p>' + data.message + '</p>';
        html += '</div>';
      html += '</div>';
    html += '</div>';

    document.getElementById("messages").innerHTML += html;
    document.getElementById("form-send-message").style.display = "";
    document.getElementById("messages").style.display = "";
    otherPersonName = data.sender;

  });

  function onUserSelected(self) {
      document.getElementById("form-send-message").style.display = "";
      document.getElementById("messages").style.display = "";
      document.getElementById("messages").innerHTML = "";
      otherPersonName = self.getAttribute("data-username");

      $.ajax({
        url: server + "/get_messages",
        method: "POST",
        data: {
          "sender": myName,
          "receiver": otherPersonName
        },
        success: function (response) {
          console.log(response);
          var messages = JSON.parse(response);
          var html = "";

          for (var a = 0; a < messages.length; a++) {
            
            if (messages[a].sender == myName) {
              html += '<div class="outgoing_msg">';
                html += '<div class="sent_msg">';
                  html += '<p>' + messages[a].message + '</p>';
                html += '</div>';
              html += '</div>';
            } else {
              html += '<div class="incoming_msg">';
                html += '<div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>';
                html += '<div class="received_msg">';
                  html += '<div class="received_withd_msg">';
                    html += '<p>' + messages[a].message + '</p>';
                  html += '</div>';
                html += '</div>';
              html += '</div>';
            }
            
          }

          document.getElementById("messages").innerHTML = html;
        }
      });
  }

  io.on("user_connected", function (username) {

    var html = "";
    html += '<div class="chat_list" data-username="' + username + '" onclick="onUserSelected(this);">';
        html += '<div class="chat_people">';
            html += '<div class="chat_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>';
            html += '<div class="chat_ib">';
              html += '<h5>' + username + '</h5>';
            html += '</div>';
        html += '</div>';
    html += '</div>';
    document.getElementById("users").innerHTML += html;
  });
</script>

</head>
<body>
<div class="container">
<h3 class=" text-center">Chat</h3>
<div class="messaging">
      <div class="inbox_msg">
        <div class="inbox_people">
          <div class="headind_srch">
            <div class="recent_heading">
              <h4>Users</h4>
            </div>
            <div class="srch_bar">
              <div class="stylish-input-group">


                <form onsubmit="return enterName();">
                  <input id="name" type="text" class="search-bar"  placeholder="Enter name" >
                  <span class="input-group-addon">
                    <button type="submit"> <i class="fa fa-plus" aria-hidden="true"></i> </button>
                  </span>
                </form>

                
                 </div>
            </div>
          </div>
          <div class="inbox_chat" id="users">
            
          </div>
        </div>
        <div class="mesgs">
          <div class="msg_history" id="messages">

          </div>
          <div class="type_msg">
            <div class="input_msg_write">

              <form onsubmit="return sendMessage();" style="display: none;" id="form-send-message">
                <input id="message" type="text" class="write_msg" placeholder="Type a message" />
                <button class="msg_send_btn" type="submit"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
              </form>

            </div>
          </div>
        </div>
      </div>
      
      
      <!-- <p class="text-center top_spac"> Design by <a target="_blank" href="#">Sunil Rajput</a></p> -->
      
    </div></div>

    <style>
    	.container{
        max-width:1170px; margin:auto;
        margin-top: 50px;
      }
img{ max-width:100%;}
.inbox_people {
  background: #f8f8f8 none repeat scroll 0 0;
  float: left;
  overflow: hidden;
  width: 40%; border-right:1px solid #c4c4c4;
}
.inbox_msg {
  border: 1px solid #c4c4c4;
  clear: both;
  overflow: hidden;
}
.top_spac{ margin: 20px 0 0;}


.recent_heading {float: left; width:40%;}
.srch_bar {
  display: inline-block;
  text-align: right;
  width: 60%; padding:
}
.headind_srch{ padding:10px 29px 10px 20px; overflow:hidden; border-bottom:1px solid #c4c4c4;}

.recent_heading h4 {
  color: #05728f;
  font-size: 21px;
  margin: auto;
}
.srch_bar input{ border:1px solid #cdcdcd; border-width:0 0 1px 0; width:80%; padding:2px 0 4px 6px; background:none;}
.srch_bar .input-group-addon button {
  background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
  border: medium none;
  padding: 0;
  color: #707070;
  font-size: 18px;
}
.srch_bar .input-group-addon { margin: 0 0 0 -27px;}

.chat_ib h5{ font-size:15px; color:#464646; margin:0 0 8px 0;}
.chat_ib h5 span{ font-size:13px; float:right;}
.chat_ib p{ font-size:14px; color:#989898; margin:auto}
.chat_img {
  float: left;
  width: 11%;
}
.chat_ib {
  float: left;
  padding: 0 0 0 15px;
  width: 88%;
}

.chat_people{ overflow:hidden; clear:both;}
.chat_list {
  border-bottom: 1px solid #c4c4c4;
  margin: 0;
  padding: 18px 16px 10px;
}
.inbox_chat { height: 550px; overflow-y: scroll;}

.active_chat{ background:#ebebeb;}

.incoming_msg_img {
  display: inline-block;
  width: 6%;
}
.received_msg {
  display: inline-block;
  padding: 0 0 0 10px;
  vertical-align: top;
  width: 92%;
 }
 .received_withd_msg p {
  background: #ebebeb none repeat scroll 0 0;
  border-radius: 3px;
  color: #646464;
  font-size: 14px;
  margin: 0;
  padding: 5px 10px 5px 12px;
  width: 100%;
}
.time_date {
  color: #747474;
  display: block;
  font-size: 12px;
  margin: 8px 0 0;
}
.received_withd_msg { width: 57%;}
.mesgs {
  float: left;
  padding: 30px 15px 0 25px;
  width: 60%;
}

 .sent_msg p {
  background: #05728f none repeat scroll 0 0;
  border-radius: 3px;
  font-size: 14px;
  margin: 0; color:#fff;
  padding: 5px 10px 5px 12px;
  width:100%;
}
.outgoing_msg{ overflow:hidden; margin:26px 0 26px;}
.sent_msg {
  float: right;
  width: 46%;
}
.input_msg_write input {
  background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
  border: medium none;
  color: #4c4c4c;
  font-size: 15px;
  min-height: 48px;
  width: 100%;
}

.type_msg {border-top: 1px solid #c4c4c4;position: relative;}
.msg_send_btn {
  background: #05728f none repeat scroll 0 0;
  border: medium none;
  border-radius: 50%;
  color: #fff;
  cursor: pointer;
  font-size: 17px;
  height: 33px;
  position: absolute;
  right: 0;
  top: 11px;
  width: 33px;
}
.messaging { padding: 0 0 50px 0;}
.msg_history {
  height: 516px;
  overflow-y: auto;
}
    </style>

    </body>
    </html>