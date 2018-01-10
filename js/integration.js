$(document).ready(function(){
    var width = 0;
    //gestion des liens des boutons
    $(".bouton").on('click', function(){
        var link = $(this).data('link');
        if(link !== ""){
            $(".page_content.active").removeClass("active");
            setTimeout(function(){
                $(document).find("#content_"+link).addClass("active");
            },100);
        }
    });
    
    //gestion du bouton retour page accueil//
    $(".head_logo").on('click', function(){
        $(".page_content.active").removeClass("active")
        if($(this).hasClass("repair_mode")){
            $(document).find("#content_home").addClass("active");
            
        }else{
            $(document).find("#content_homeE").addClass("active");
            
        }
    });
    
    $(".inge_choice").on('click', function(){
        $(".head_logo").addClass("inge_mode");
        $(".head_logo").removeClass("repair_mode");
        $(document).find(".exit_bloc").removeClass("hidden");
    });
    $(".repair_choice").on('click', function(){
        $(".head_logo").addClass("repair_mode");
        $(".head_logo").removeClass("inge_mode");
        $(document).find(".exit_bloc").removeClass("hidden");
    });
    $(".exit_bloc").on('click', function(){
        $(this).addClass("hidden");
        $(".page_content.active").removeClass("active");
        setTimeout(function(){
            $(document).find("#content_role").addClass("active");
        },100);
    });
    
    
    //----------------------------------------------------//
     $(".calibration_bt").on('click', function(){
        $(".overlay_choice").removeClass("hidden");
    });
    
    $(".overlay_choice .go_calib").on('click', function(){
        $(".overlay_choice").addClass("hidden");
        $(".page_content.active").removeClass("active");
        setTimeout(function(){
            $(document).find("#content_calibration").addClass("active");
        },100);
    });
    
    $(".overlay_choice .go_download").on('click', function(){
        $(".overlay_choice").addClass("hidden");
        $(".page_content.active").removeClass("active");
        setTimeout(function(){
            $(document).find("#content_download").addClass("active");
        },100);
    });
    
    $(".bt_section").on("click", function(){
        if($(this).hasClass("diagnostic")){
            $(".bt_section").addClass("opacity_off");
            $(this).removeClass("opacity_off");
            $(".bloc_container .bloc").addClass('hidden');
            $(".bloc_container .information_finaltest").addClass('hidden');
            $(".login_diag").removeClass("hidden");
        }
        if($(this).hasClass("history")){
            $(".bt_section").addClass("opacity_off");
            $(this).removeClass("opacity_off");
            $(".bloc_container .bloc").addClass('hidden');
            $(".bloc_container .information_diag").addClass('hidden');
            $(".bloc_container .information_finaltest").addClass('hidden');
            $(".history_search").removeClass("hidden");
        }
        if($(this).hasClass("finaltest")){
            $(".bt_section").addClass("opacity_off");
            $(this).removeClass("opacity_off");
            $(".bloc_container .bloc").addClass('hidden');
            $(".bloc_container .information_diag").addClass('hidden');
            $(".login_finaltest").removeClass("hidden");
        }
    });
    
    $(".popup_test_fw .bt_yes").on("click", function(){
        $(".download_test_fw_content").removeClass("hidden");
        $(".popup_test_fw").addClass("hidden");
    });
    
    $(".popup_test_fw_final .bt_yes").on("click", function(){
        $(".download_test_fw_content_final").removeClass("hidden");
        $(".popup_test_fw_final").addClass("hidden");
    });
    
    
    
});