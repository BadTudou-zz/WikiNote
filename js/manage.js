/*
    Copyright © JiangTaoTao, 2016
    All rights reserved

    Name    :   manage.js
    By      :   BadTudu
    Date    :   2016年5月05日20:13:05
    Note    :   WikiNote管理页面
*/
$(function() 
{
    //复杂菜单效果
    $(".menu_list").hide();
    $(".menu_list_first").show();
    $(".a_list").click(function() 
    {
        var len = $('.a_list').length;
        var index = $(".a_list").index(this);
        for (var i = 0; i < len; i++) 
        {
            if (i == index) 
            {
                $('.menu_list').eq(i).slideToggle(300);
            } 
            else 
            {
                $('.menu_list').eq(i).slideUp(300);
            }
        }
    });
})

//点击显示复杂菜单和隐藏简洁菜单
$(function() 
{
    $(".menu-oc").click(function() 
    {
        $(".leftmenu1").animate(
        {
            left: "-292px"
        });
        $(".leftmenu2").animate(
        {
            left: "0px"
        });
        $(".rightcon").css("margin-left", "52px")
    })
    $(".menu-oc1").click(function() 
    {
        $(".leftmenu1").animate({
            left: "0px"
        });
        $(".leftmenu2").animate({
            left: "-192px"
        });
        $(".rightcon").css("margin-left", "240px");
    })
})

//简单菜单移动效果
$(function() 
{
    $(".j_menu_list").hide();
    $(".j_a_list").hover(function() 
    {
        $(".leftmenu2 ul li").hover(function() 
        {
            $(this).find('.j_menu_list').show();
        }, function() {
            $(this).find('.j_menu_list').hide();
        });
    })
})

$(document).ready(function($) 
{
    console.log('ready');
    $('#topmenu-type').hover(function() 
    {
        $('#iframe-src').attr('src','../html/typelist.html');   
        /* Stuff to do when the mouse enters the element */
    }, function() {
        /* Stuff to do when the mouse leaves the element */
    });
    $('#topmenu-user').hover(function() 
    {
        $('#iframe-src').attr('src','../html/userlist.html');
        /* Stuff to do when the mouse enters the element */
    }, function() {
        /* Stuff to do when the mouse leaves the element */
    });
    $('#topmenu-note').hover(function() 
    {
        $('#iframe-src').attr('src','../html/notelist.html');
        /* Stuff to do when the mouse enters the element */
    }, function() {
        /* Stuff to do when the mouse leaves the element */
    });
    $('a').click(function(event) 
    {
        var title = $(this).text();
        switch(title)
        {
            case '所有用户':

        }
    });
});