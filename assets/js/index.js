'use strict';
import $ from 'jquery';

$(document).ready(function() {
    var main_route = (window.location.pathname.split("/")[1]);
    if(main_route == null || main_route == ""){
        main_route = "dashboard";
    }
    $('.navbar_' + main_route).addClass('active');
});

$(() => {

  var $createProjectForm = $(".project-create-form");

  if ($createProjectForm.length) {
    $createProjectForm.on("submit", function(event) {
      event.preventDefault();
      var projectName = $('.project_name').val();
      var projectStatus = $('.project_status').val();
      var projectTimeline = $('.project_timeline').val();
      var projectUser = $('.project_user').val();

      var url = $createProjectForm.data('path');
      $.ajax({
        url,
        method: 'POST',
        data: {
          projectName,
          projectStatus,
          projectTimeline,
          projectUser,
        }
      }).done((response) => {
        console.log('Project created');
        location.reload();
      }).fail((response) => {
        console.log('Project creation failed');
      }).always((response) => {
        console.log('executed');
        });
    });
  }

  var $form = $(".chat-input");

  if ($form.length) {
    $(".chat-messages").scrollTop($(".chat-messages")[0].scrollHeight);
    $form.on("submit", function(event) {
      event.preventDefault();
      $(".chat-messages").scrollTop($(".chat-messages")[0].scrollHeight);
      var $target = $('.chat-input-content');
      var chatid = $target.data("chatid");
      var userid = $target.data('userid');
      var url = $target.data('path');
      
      $.ajax({
        url,
        method: 'POST',
        data: {
          chatid,
          userid,
          content: $target.val(),
        }
      }).done((response) => {
        console.log('Message created');
        location.reload();
        $('.chat-input-content').val('')
      }).fail((response) => {
        console.log('Message creation failed');
      }).always((response) => {
        console.log('executed');
        });
    });
  }

  var $openChat = $('.message-box');

  $openChat.on('dblclick', ({ currentTarget }) => {   
    var $target = $(currentTarget);        
    var url = $target.data('path');

    $.ajax({
      url,
      method: 'POST',
    }).done((response) => {
      console.log('Chat opened');
      window.location = url;
    }).fail((response) => {
      console.log('Chat opened failed');
    }).always((response) => {
    });
  });

  var $createChat = $('.online-box');

  $createChat.on('dblclick', ({ currentTarget }) => {   
    var $target = $(currentTarget);        
    var id = $target.data("id");
    var url = $target.data('path');

    $.ajax({
      url,
      method: 'POST',
      data: {
        id,
      }
    }).done((response) => {
      console.log('Chat created');
    }).fail((response) => {
      console.log('Chat creation failed');
    }).always((response) => {
      
    });
  });

  var dropDown = document.querySelector('.dropdown');
  var dropdowned = false;
  
  dropDown.addEventListener('click', function () {           
    if(dropdowned == false){          
      document.querySelector('.dropdown-menu').classList.add('show');
      dropdowned = true;
    } else if(dropdowned){
      document.querySelector('.dropdown-menu').classList.remove('show');
      dropdowned = false;
    }
  });
  if (window.location.pathname === "/" || window.location.pathname === "/projects") {
    
    var listView = document.querySelector('.list-view');
    var gridView = document.querySelector('.grid-view');
    var projectsList = document.querySelector('.project-boxes');
    
    listView.addEventListener('click', function () {
      gridView.classList.remove('active');
      listView.classList.add('active');
      projectsList.classList.remove('jsGridView');
      projectsList.classList.add('jsListView');
    });
    
    gridView.addEventListener('click', function () {
      gridView.classList.add('active');
      listView.classList.remove('active');
      projectsList.classList.remove('jsListView');
      projectsList.classList.add('jsGridView');
    });

    if(window.location.pathname === "/projects")
    {
      var $createProjectBtn = $(".project-createOpenBtn");
      var $createProjectSection = $(".project-project");
      var createActive = false;
      $createProjectBtn.on('click', function() {
        if(createActive === false){
          $createProjectSection.addClass('active');
          $createProjectBtn.addClass('active');
          createActive = true;
        }
        else if(createActive){
          $createProjectSection.removeClass('active');
          $createProjectBtn.removeClass('active');
          createActive = false;
        }
      });
    }
    
    if(window.location.pathname === "/projects")
      return;
    document.querySelector('.messages-btn').addEventListener('click', function () {
      document.querySelector('.messages-section').classList.add('show');
    });
    
    document.querySelector('.messages-close').addEventListener('click', function() {
      document.querySelector('.messages-section').classList.remove('show');
    });
  }
});