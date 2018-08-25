
<?php require "backend/controller.php"; ?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Baraka</title>

    <!-- Bootstrap v 3.1.1-->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="fonts/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
  
  .box{
  background-color: #d3d3d3;
  border: 1px solid blue;
  }

  .navbar{
  border-radius: 5px;
  background: #101010;
  width: 70%;
  margin-right: auto;
  margin-left: auto;
  display: inline-flex;
  }

  

  @media(max-width: 540px){.navbar{padding: 5px 0.5px; 
  font-size: 90%; 
  line-height: 1;
  border-radius: 5px;
  background: #101010;
  width: 90%;
  margin-right: auto;
  margin-left: auto;
  display: inline-flex;
  white-space: nowrap;}
  }

  .btn-info{
   background: #006699;
   border: 0px none rgb(255, 255, 255);
  }

  .marginTop{
  margin-top: 20px;
  color: white;
  }


 .strike {
    display: block;
    text-align: center;
    overflow: hidden;
    white-space: nowrap; 
}
.strike > span {
    position: relative;
    display: inline-block;
}
.strike > span:before, .strike > span:after {
    content: "";
    position: absolute;
    top: 50%;
    width: 320px;
    height: 1px;
    background: #D3D3D3;
}
.strike > span:before {
    right: 100%;
    margin-right: 15px;
}
.strike > span:after {
    left: 100%;
    margin-left: 15px;
}


  .nav-pills{
  margin-top: 40px;
 }

 .thumbnail{
  border: 0 none;
  box-shadow: none;
  background: transparent;
 }

  .thumbnails p{
  font-size: small;
  } 

  .span4{
  max-width: 220px;
  height: 110px;
  margin-right: 40px;
  margin-bottom: 20px;
  float: left;
  display: inline-flex;
  }

  li img{
    border-radius: 4px;
  }

  .caption{
  float: left;
  white-space: nowrap;
  }

  .caption .btn-block{
    float: left;
    width: 100px;
    box-sizing: border-box;
    text-align: center;
    background: #006699 none repeat scroll 0% 0% / auto padding-box border-box;
    border: 0px none rgb(255, 255, 255);
    border-radius: 2px 2px 2px 2px;
    font: normal normal 400 normal 14.4px / normal PROXRegular, Arial;
    outline: rgb(255, 255, 255) none 0px;
    padding: 4px 20px 3px 15px;
  }

  .inner{
  display: inline-flex;
  margin-left: auto;
  margin-right: auto;
  }
  
  a:hover{
    color: green;
  }
 
    @media(max-width: 540px){.btn-block{padding: 5px 0.5px; 
    font-size: 80%; 
    line-height: 1;
    position: relative;
    padding-left: 10px;
    padding-right: 10px;}
  }
  
  @media(max-width: 540px){.span4{padding: 5px 0.5px; 
    font-size: 90%; 
    line-height: 1;
    position: relative;
    padding-left: 10px;
    padding-right: 10px;
    margin-bottom: 0px;
    height: 140px;}
  }
  @media(max-width: 540px){.thumbnails{padding: 5px 0.5px; 
    line-height: 1;
    position: relative;}
  }
  @media(max-width: 540px){.inner{padding: 5px 0.5px; 
    font-size: 90%; 
    line-height: 2;
    position: relative;
    overflow: hidden;}
  }
  

 #cont{
  margin-top: 100px;
 }

  #topContainer{
    overflow: auto;
    height: 300px;
    width: 100%;
    background-size: cover;
  }

  #topRow{
    margin-top: 150px;
    color: #101010;
    text-align: center;
  }

  #formWidth{
    width: 70%;
    margin-left: auto;
    margin-right: auto;
    margin-bottom: 130px;
  }
 


  </style>
  </head>
  <body>

 <!--navigation-->
<nav class="navbar navbar-inverse navbar-fixed-top">
   <div class="container">
    <div class="navbar-header">
      <a class="navbar-brand" style="color: #969696;"><small><i>The favorite christian music hub </i></small>|</a>
    </div>
   <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
      <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span> 
    </button>

      <div class="collapse navbar-collapse">
      <ul class="nav navbar-nav">
        <li class="active"><a href="index.html"><span class="fa fa-lg fa-home"></span> Home</a></li>
        <li><a href="about.html"><span class="fa fa-lg fa-paperclip"></span> About</a></li> 
        <li><a href="dmca.html"><span class="glyphicon glyphicon-copyright-mark"></span> Privacy Policy</a></li>
        <li><a href="contactus.html"><span class="fa fa-lg fa-envelope-o"></span> Contact</a></li> 
      </ul>    
    </div>
  </div>
</nav>


<!--headers-->
<div class="container">
  <div class="row">
 
  <div id="topRow">
         <p class="lead">Baraka</p>
         <p>Love music, Love Jesus</p>
         <p class="bold">Free mp3 download</p>

 <!--search form-->      
     <form class="marginTop" action="" method="">
           <div class="form-horizontal">
              <div class="input-group" id="formWidth">
                  <input type="text" class="form-control"  placeholder="Type here to search song or artiste" required="required" style="height:30px; border-radius:0px; border: 1px solid #D3D3D3">
                  <span class="input-group-btn" style="width:1;">
                      <button type="button" class="btn btn-info btnSearch" style="height: 30px; width: 75px; border-radius:0px;">
                      SEARCH
                      </button>
                  </span>
              </div>
            </div>      
      </form>

<!--centered text hr-->
<div class="strike">
   <span>Top S<span class="glyphicon glyphicon-headphones"></span>ngs this Week</span>
</div>  <h3 class="text-center"></h3>

<!--thumbnails-->  
    <div class="inner" style="background: linear-gradient( #FFFFFF, #FAFAFA, #F0F0F0); border-radius: 5px;">
      <ul class="thumbnails">

          <li class="span4">
              <div class="thumbnail">
                <img src="img/racet.jpg" alt="ALT NAME">
              </div>
              <div class="caption">
                  <h5><span class="fa fa-check-circle"> Praise Him</span></h5>
                  <p><span class="fa fa-user"> Race T</span></p>
                  <p align="center"><a href="" class="btn btn-primary btn-block"><span class="fa fa-download"></span>Download</a></p>
              </div>
            </li>
            
            <li class="span4">
              <div class="thumbnail">
                <img src="img/jremiah.jpg" alt="ALT NAME">
              </div>
                <div class="caption">
                  <h5><span class="fa fa-check-circle"> Life</span></h5>
                  <p><span class="fa fa-user"> Jremiah</span></p>
                  <p align="center"><a href="" class="btn btn-primary btn-block"><span class="fa fa-download"></span>Download</a></p>
                </div>
            </li>

            <li class="span4">
            <div class="thumbnail">
                <img src="img/mp3/mp31.png" alt="ALT NAME">
              </div>
               <div class="caption">
                  <h5><span class="fa fa-check-circle"> Amazzi Gobulamu</span></h5>
                  <p><span class="fa fa-user"> Andrea Presson</span></p>
                  <p align="center"><a href="" class="btn btn-primary btn-block"><span class="fa fa-download"></span>Download</a></p>
                </div>
             </li>

 <br />

            
                <li class="span4">
                <div class="thumbnail">
                  <img src="img/mottta.jpg" alt="ALT NAME">
                </div>
                <div class="caption">
                    <h5><span class="fa fa-check-circle"> Obrigado</span></h5>
                    <p><span class="fa fa-user"> Motta Africa</span></p>
                    <p align="center"><a href="" class="btn btn-primary btn-block"><span class="fa fa-download"></span>Download</a></p>
                  </div>
                </li>


                <li class="span4">
                <div class="thumbnail">
                  <img src="img/babygloria.jpg" alt="ALT NAME">
                </div>
                <div class="caption">
                    <h5><span class="fa fa-check-circle"> DNA</span></h5>
                   <p><span class="fa fa-user"> Baby Gloria</span></p>
                    <p align="center"><a href="" class="btn btn-primary btn-block"><span class="fa fa-download"></span>Download</a></p>
                  </div>
                </li>
 
                <li class="span4">
                <div class="thumbnail">
                  <img src="img/deejayawar.jpg" alt="ALT NAME">
                </div>
                <div class="caption">
                    <h5><span class="fa fa-check-circle"> Represent Jesus</span></h5>
                    <p><span class="fa fa-user"> Deejay Awar</span></p>
                    <p align="center"><a href="" class="btn btn-primary btn-block"><span class="fa fa-download"></span>Download</a></p>
                  </div>
                </li>
        </ul>
</div>
     
  </div>
  </div>
</div>
</div>

<!--footer-->          
<div class="container-fluid text-center" id="cont">
  <hr>  
  <div class="row">
    <div class="col-lg-12">
      <div class="col-md-4">
        <ul class="nav nav-pills nav-stacked">
          
          <li><a href="advertise.html"><span class="fa fa-lg fa-bar-chart-o"></span> Advertise</a></li>
        </ul>
      </div>
       <div class="col-md-4">
        <ul class="nav nav-pills nav-stacked">
        <p><strong><i class="fa fa-tags"></i> Today's scripture:</strong></p>
        <p>Blessed is the one who reads the words of this prophecy, and blessed are those who hear it and take to heart what is written in it, because the time is near - Revelation 1:3</p>             
        </ul>
      </div> 
      <div class="col-md-4">
        <ul class="nav nav-pills nav-stacked">
          <p>The favorite christian music hub
          <br/>
          Love music, Love Jesus
          <br/>
          <br/>
          <p><span class="fa fa-lg fa-phone"></span> +256 775 714 654</p> 
          <p><a href="https://web.facebook.com/pius.odatum" target="_blank"><button type="submit" class="btn btn-info" style="height: 22px; padding-top: 1px">Like Us <i class="fa fa-facebook" aria-hidden="true"></i>
</button></a> <a href="https://www.twitter.com/pius_odatum" target="_blank"><button type="submit" class="btn btn-info" style="height: 22px; padding-top: 1px;">Follow @barakaug <i class="fa fa-twitter" aria-hidden="true"></i></button></a>
          </p>
        </ul>
      </div> 

    </div>
  </div>
 

 <p style="color: #BEBEBE">Copyright © barakaug 2018. All Rights Reserved</p>
    </div>
      
    </div>
  
</div>

       
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    

  
  </body>
</html>