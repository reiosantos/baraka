<!--headers-->
<div class="container">
    <div class="row">
        <div id="topRow">
            <h4 class="text-danger"><?php if (isset($error)) { echo $error; } ?></h4>

            <p class="lead">Baraka</p>
            <p>Love music, Love Jesus</p>
            <p class="bold">Free mp3 download</p>

            <!--search form-->
            <form class="marginTop" action="" method="">
                <div class="form-horizontal">
                    <div class="input-group" id="formWidth">
                        <input type="text" class="form-control"
                               placeholder="Type here to search song or artiste" required="required"
                               style="height:30px; border-radius:0px; border: 1px solid #D3D3D3">
                        <span class="input-group-btn" style="width:1;">
                      <button type="button" class="btn btn-info btnSearch"
                              style="height: 30px; width: 75px; border-radius:0px;">
                      SEARCH
                      </button>
                  </span>
                    </div>
                </div>
            </form>

            <form class="marginTop" enctype="multipart/form-data" action="/artist"
                  method="post">
                <input name="name" type="text" placeholder="Song name" />
                <select name="artist">
                    <?php
                        foreach ($artists as $artist) {
                            echo '<option value="'. $artist->getId().'">'. $artist->getName() .'</option>';
                        }
                    ?>
                </select>
                <input name="details" type="text" />
                <input name="photo" type="file" />
                <button type="submit" name="submit">Submit</button>
            </form>

            <!--centered text hr-->
            <div class="strike">
                <span>Top S<span class="glyphicon glyphicon-headphones"></span>ngs this Week</span>
            </div>
            <h3 class="text-center"></h3>

            <!--thumbnails-->
            <div class="inner"
                 style="background: linear-gradient( #FFFFFF, #FAFAFA, #F0F0F0); border-radius: 5px;">
                <ul class="thumbnails">

                    <li class="span4">
                        <div class="thumbnail">
                            <img src="/app/static/img/deejayawar.jpg" alt="ALT NAME">
                        </div>
                        <div class="caption">
                            <h5><span class="fa fa-check-circle"> Represent Jesus</span></h5>
                            <p><span class="fa fa-user"> Deejay Awar</span></p>
                            <p align="center"><a href="" class="btn btn-primary btn-block"><span
                                class="fa fa-download"></span>Download</a></p>
                        </div>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</div>
</div>
