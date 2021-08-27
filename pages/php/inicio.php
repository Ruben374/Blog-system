

<div class="main-banner header-text">
      <div class="container-fluid">
        <div class="owl-banner owl-carousel">
        <?php teste()?>
          
        </div>
      </div>
    </div>
 
<section class="blog-posts">
      <div class="container">
        <div class="row">
          
          <div class="col-lg-8">
            <div class="all-blog-posts">
              <div class="row">
              
              <?php getPosts() ?>
              <div class="col-lg-12">
                  <ul class="page-numbers">
                   <?php Pageslist();?>

                  </ul>
                </div>
              </div>
            </div>
          
          </div>
        
          <div class="col-lg-4">
            <div class="sidebar">
              <div class="row">
                <div class="col-lg-12">
                  <div class="sidebar-item search">
                    <form id="search_form" name="gs" method="POST" action="busca">
                      <input type="text" name="busca" class="searchText" placeholder="type to search..." autocomplete="on" required="required">
                     
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
      </div>
    </section>







<ul class="pagination justify-content-center">
  <?php PagesList();?>
</ul>
