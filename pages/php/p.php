<?php 
  $dados = getCompletePost(); 
  $data = getData(); 
  countViews($dados['id']);
?>

 
    <div class="heading-page header-text">
      <section class="page-heading">
        <div class="container">
          <div class="row">
            <div class="col-lg-12">
              <div class="text-content">
                <h4>Post Details</h4>
                <h2>Single blog post</h2>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
    
    <!-- Banner Ends Here -->

    <section class="call-to-action">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="main-content">
              <div class="row">
                <div class="col-lg-8">
                  <span>Stand Blog HTML5 Template</span>
                  <h4>Creative HTML Template For Bloggers!</h4>
                </div>
                <div class="col-lg-4">
                  <div class="main-button">
                    <a rel="nofollow" href="https://templatemo.com/tm-551-stand-blog" target="_parent">Download Now!</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>


    <section class="blog-posts grid-system">
      <div class="container">
        <div class="row">
          <div class="col-lg-8">
            <div class="all-blog-posts">
              <div class="row">
                <div class="col-lg-12">
                  <div class="blog-post">
                    <div class="blog-thumb">
                      <img src="<?php echo $dados['imagem'];?>" alt="">
                    </div>
                    <div class="down-content">
                    <span>               <li style="list-style:none"><?php echo getCategorianome($dados['categoria']); ?></li>                   </span>

                    <a href="<?php echo $dados['subtitulo'];?>"><h4><?php echo $dados['titulo'];?></h4></a> 

                      <ul class="post-info">
                        <li><?php echo getData_fromUser($dados['id_postador'], "Nome","id"); ?></li>
                        <li><?php echo calculaDias($data, $dados['data']);?></li>
                        <li> <?php echo getComents_FromPost($dados['id']);?> Comments</li>
                      </ul>
                      <p>    <?php echo $dados['postagem'];?> </p>
                      <div class="post-options">
                        <div class="row">
                          <div class="col-6">
                            <ul class="post-tags">
                              <li><i class="fa fa-tags"></i><a  href="categoria/<?php echo $dados['categoria'];?>" ><?php echo getCategorianome($dados['categoria']); ?></li>  </a> </li>
              
                            </ul>
                          </div>
                          <div class="col-6">
                            <ul class="post-share">
                              <li><i class="fa fa-share-alt"></i></li>
                              <li><a href="#">Facebook</a>,</li>
                              <li><a href="#"> Twitter</a></li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              





                <div class="col-lg-12">
                  <div class="sidebar-item comments">
                    <div class="sidebar-heading">
                      <h2>4 comments</h2>
                    </div>
                    <div class="content">
                      <ul>
                      <?php getComentPost($dados['id']);?>
                      </ul>
                    </div>
                  </div>
                </div>






                <div class="col-lg-12">
                  <div class="sidebar-item submit-comment">
                    <div class="sidebar-heading">
                      <h2>Your comment</h2>
                    </div>
                    <div class="content">
                      <form id="comment"  method="post">
                        <div class="row">
                          <div class="col-md-6 col-sm-12">
                            <fieldset>
                              <input name="nome" type="text" id="name" placeholder="Your name" required="">
                            </fieldset>
                          </div>
                      
                          <div class="col-lg-12">
                            <fieldset>
                              <textarea name="comentario" rows="6" id="message" placeholder="Type your comment" required=""></textarea>
                            </fieldset>
                          </div>
                          <div class="col-lg-12">
                            <fieldset>
                              <button type="submit" id="form-submit" class="main-button">Submit</button>
                              <input type="hidden" name="env" value="comentario">
                            </fieldset>
                          </div>
                        </div>
                      </form>
                      <?php sendComent($dados['id'], $dados['subtitulo']);?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
          </div>
          <div class="col-lg-4">
            <div class="sidebar">
              <div class="row">
                <div class="col-lg-12">
                  <div class="sidebar-item search">
                    <form id="search_form" name="gs" method="GET" action="busca">
                      <input type="text" name="q" class="searchText" placeholder="type to search..." autocomplete="on">
                    </form>
                  </div>
                </div>
                <div class="col-lg-12">
                  <div class="sidebar-item recent-posts">
                    <div class="sidebar-heading">
                      <h2>Recent Posts</h2>
                    </div>
                    <div class="content">
                      <ul>
                     <?php getMostpopularposts()?>
                      </ul>
                    </div>
                  </div>
                </div>
                <div class="col-lg-12">
                  <div class="sidebar-item categories">
                    <div class="sidebar-heading">
                      <h2>Categories</h2>
                    </div>
                    <div class="content">
                      <ul style="text-transform:uppercase">
                      <?php getCategoriasblog();?>
                       
                      </ul>
                    </div>
                  </div>
                </div>
                <div class="col-lg-12">
                  <div class="sidebar-item tags">
                    <div class="sidebar-heading">
                      <h2>Tag Clouds</h2>
                    </div>
                    <div class="content">
                      <ul>
                    
                        <li><a href="#">Responsive</a></li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>
    </section>

    
    