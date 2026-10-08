<div class="card bg-secondary shadow">   
        <div class="card-header bg-white border-0"> 
                <div class="row align-items-center">
                    <div class="col-8">
                          <h3 class="mb-0"><?php echo $title ?></h3>
                    </div>
                <div class="col-4 text-right"> 
                    <a href="<?php echo site_url('member/index')?>" class="btn btn-sm btn-primary">Home</a>
                </div>
            </div>
        </div>
    <div class="card-body"> 

<h2>level 1 Of <?php echo $this->session->user_id; ?></h2>
<div class="row">
    <div class="card-body table-responsive">
    <table id="datatable-buttons" class="table align-middle table-nowrap table-check">
        <thead class="thead-light">
              <tr>
                <th>Name</th>
                <th>ID</th>
                <th>Phone</th>
                <th>Sponsor</th>
                <th>Signup package</th>
                <th>Join Date</th>
               </tr> 
            </thead>
            <tbody>
               <?php

                   $level1="select * from member where sponsor=".$this->session->user_id;
                    $query1 = $this->db->query($level1);
                      foreach($query1->result() as $row1)
                          {
                          ?>
                            <tr>
                              <td><?php echo $row1->name; ?></td>
                              <td><?php echo $row1->id; ?></td>
                              <td><?php echo $row1->phone; ?></td>
                              <td><?php echo $row1->sponsor; ?></td>
                               <td><?php echo $row1->signup_package; ?></td>
                               <td><?php echo $row1->join_time; ?></td>
                             </tr>
                          <?php
                            } 
                          ?>
            </tbody>
          </table>
        </div>
      </div>


                      <!-- end of first level -->

    <div class="Container">
       <h2>level 2 of <?php echo $this->session->user_id; ?></h2>
   <div class="table-responsive">
    <table class="table align-items-center table-flush">
        <thead class="thead-light">
              <tr>
                <th>Name</th>
                <th>ID</th>
                <th>Phone</th>
                <th>Sponsor</th>
                <th>Signup package</th>
                <th>Join Date</th>
               </tr>
            </thead>
            <tbody>
              <?php
                 $level1="select * from member where sponsor=".$this->session->user_id;
                 $query1 = $this->db->query($level1);
                  foreach($query1->result() as $row1)
                     {
                      ?>
                      <?php
                          $array1= array('id' => $row1->id);
                          $level2="select * from member where sponsor='". $array1['id']."'";
                          $query2 = $this->db->query($level2);
                        ?>
                       <?php
                          foreach($query2->result() as $row2)
                           {
                       ?>

                         <tr>
                            <td><?php echo $row2->name; ?></td>
                            <td><?php echo  $row2->id; ?></td> 
                            <td><?php echo $row2->phone; ?></td>
                            <td><?php echo $row2->sponsor; ?></td>
                            <td><?php echo $row2->signup_package; ?></td>
                            <td><?php echo $row1->join_time; ?></td>
                          </tr>
                      <?php
                        }
                        }
                      ?>
            </tbody>
          </table>
        </div>
      </div>

                      <!-- end of second level -->
                      <!-- third level start -->
<div class="Container">
<h2>level 3 of <?php echo $this->session->user_id; ?></h2>
 <div class="table-responsive">
    <table class="table align-items-center table-flush">
        <thead class="thead-light">
       <tr>
                <th>Name</th>
                <th>ID</th>
                <th>Phone</th>
                <th>Sponsor</th>
                <th>Signup package</th>
                <th>Join Date</th>
       </tr>
     </thead>
       <tbody>
         <?php
           $level1="select * from member where sponsor=".$this->session->user_id;
            $query1 = $this->db->query($level1);
              foreach($query1->result() as $row1)
              {
              ?>
              <?php
                  $array1= array('id' => $row1->id);
                  $level2="select * from member where sponsor='". $array1['id']."'";
                   $query2 = $this->db->query($level2);
                   foreach($query2->result() as $row2)
                     {
                        $array2= array('id' => $row2->id);
                        $level3="select * from member where sponsor='". $array2['id']."'";
                        $query3 = $this->db->query($level3);
                        foreach($query3->result() as $row3)
                        {
                        ?>
                        <tr>
                        <td><?php echo $row3->name; ?></td>
                        <td><?php echo  $row3->id; ?></td> 
                        <td><?php echo $row3->phone; ?></td>
                        <td><?php echo $row3->sponsor; ?></td>
                        <td><?php echo $row3->signup_package; ?></td>
                        <td><?php echo $row1->join_time; ?></td>

                        </tr>
                      <?php
                             }
                           }
                         }
                      ?>
        </tbody>
    </table>
  </div>
</div>

<!-- third level end -->
<!-- level four start -->
<div class="Container">
<h2>level 4 of <?php echo $this->session->user_id; ?></h2>
<div class="table-responsive">
    <table class="table align-items-center table-flush">
        <thead class="thead-light">
        <tr>
                <th>Name</th>
                <th>ID</th>
                <th>Phone</th>
                <th>Sponsor</th>
                <th>Signup package</th>
                <th>Join Date</th>
        </tr>
        </thead>
          <tbody>
            <?php
            $level1="select * from member where sponsor=".$this->session->user_id;
             $query1 = $this->db->query($level1);
             foreach($query1->result() as $row1)
               {
                ?>
                <?php
                  $array1= array('id' => $row1->id);
                  $level2="select * from member where sponsor='". $array1['id']."'";
                  $query2 = $this->db->query($level2);
                  foreach($query2->result() as $row2)
                    {
                      $array2= array('id' => $row2->id);
                       $level3="select * from member where sponsor='". $array2['id']."'";
                        $query3 = $this->db->query($level3);
                          foreach($query3->result() as $row3)
                            {
                              $array3= array('id' => $row3->id);
                              $level4="select * from member where sponsor='". $array3['id']."'";
                              $query4 = $this->db->query($level4);
                              foreach($query4->result() as $row4)
                               {
                                 ?>

                                   <tr>
                                     <td><?php echo $row4->name; ?></td>
                                     <td><?php echo  $row4->id; ?></td> 
                                      <td><?php echo $row4->phone; ?></td>
                                     <td><?php echo $row4->sponsor; ?></td>
                                     <td><?php echo $row4->signup_package; ?></td>
                                     <td><?php echo $row1->join_time; ?></td>
                                   </tr>
                                 <?php
                             }
                           }
                         }
                       }
                             ?>
          </tbody>
      </table>
    </div>
  </div>
                     <!--  level four end -->
                     <!-- level five start -->
<div class="Container">
<h2>level 5 of <?php echo $this->session->user_id; ?></h2>
<div class="table-responsive">
    <table class="table align-items-center table-flush">
        <thead class="thead-light">
        <tr>
                <th>Name</th>
                <th>ID</th>
                <th>Phone</th>
                <th>Sponsor</th>
                <th>Signup package</th>
                <th>Join Date</th>
        </tr>
        </thead>
          <tbody>
            <?php
            $level1="select * from member where sponsor=".$this->session->user_id;
             $query1 = $this->db->query($level1);
             foreach($query1->result() as $row1)
               {
                ?>
                <?php
                  $array1= array('id' => $row1->id);
                  $level2="select * from member where sponsor='". $array1['id']."'";
                  $query2 = $this->db->query($level2);
                  foreach($query2->result() as $row2)
                    {
                      $array2= array('id' => $row2->id);
                       $level3="select * from member where sponsor='". $array2['id']."'";
                        $query3 = $this->db->query($level3);
                          foreach($query3->result() as $row3)
                            {
                              $array3= array('id' => $row3->id);
                              $level4="select * from member where sponsor='". $array3['id']."'";
                              $query4 = $this->db->query($level4);
                              foreach($query4->result() as $row4)
                               {
                                $array4= array('id' => $row4->id);
                              $level5="select * from member where sponsor='". $array4['id']."'";
                              $query5 = $this->db->query($level5);
                              foreach($query5->result() as $row5)
                               {
                                 ?>

                                   <tr>
                                     <td><?php echo $row5->name; ?></td>
                                     <td><?php echo  $row5->id; ?></td> 
                                      <td><?php echo $row5->phone; ?></td>
                                     <td><?php echo $row5->sponsor; ?></td>
                                     <td><?php echo $row5->signup_package; ?></td>
                                     <td><?php echo $row5->join_time; ?></td>
                                   </tr>
                                 <?php
                               }
                             }
                           }
                         }
                       }
                             ?>
          </tbody>
      </table>
    </div>
  </div>
<!--   level five end -->
<!-- level six start -->
<div class="Container">
<h2>level 6 of <?php echo $this->session->user_id; ?></h2>
<div class="table-responsive">
    <table class="table align-items-center table-flush">
        <thead class="thead-light">
        <tr>
                <th>Name</th>
                <th>ID</th>
                <th>Phone</th>
                <th>Sponsor</th>
                <th>Signup package</th>
                <th>Join Date</th>
        </tr>
        </thead>
          <tbody>
            <?php
            $level1="select * from member where sponsor=".$this->session->user_id;
             $query1 = $this->db->query($level1);
             foreach($query1->result() as $row1)
               {
                ?>
                <?php
                  $array1= array('id' => $row1->id);
                  $level2="select * from member where sponsor='". $array1['id']."'";
                  $query2 = $this->db->query($level2);
                  foreach($query2->result() as $row2)
                    {
                      $array2= array('id' => $row2->id);
                       $level3="select * from member where sponsor='". $array2['id']."'";
                        $query3 = $this->db->query($level3);
                          foreach($query3->result() as $row3)
                            {
                              $array3= array('id' => $row3->id);
                              $level4="select * from member where sponsor='". $array3['id']."'";
                              $query4 = $this->db->query($level4);
                              foreach($query4->result() as $row4)
                               {
                              $array4= array('id' => $row4->id);
                              $level5="select * from member where sponsor='". $array4['id']."'";
                              $query5 = $this->db->query($level5);
                              foreach($query5->result() as $row5)
                               {
                              $array5= array('id' => $row5->id);
                              $level6="select * from member where sponsor='". $array5['id']."'";
                              $query6 = $this->db->query($level6);
                              foreach($query6->result() as $row6)
                               {
                                 ?>

                                   <tr>
                                     <td><?php echo $row6->name; ?></td>
                                     <td><?php echo  $row6->id; ?></td> 
                                      <td><?php echo $row6->phone; ?></td>
                                     <td><?php echo $row6->sponsor; ?></td>
                                     <td><?php echo $row6->signup_package; ?></td>
                                     <td><?php echo $row6->join_time; ?></td>
                                   </tr>
                                 <?php
                               }
                             }
                           }
                         }
                       }
                     }
                             ?>
          </tbody>
      </table>
    </div>
  </div>
<!--   level six end -->
<!-- level seven start -->
<div class="Container">
<h2>level 7 of <?php echo $this->session->user_id; ?></h2>
<div class="table-responsive">
    <table class="table align-items-center table-flush">
        <thead class="thead-light">
        <tr>
                <th>Name</th>
                <th>ID</th>
                <th>Phone</th>
                <th>Sponsor</th>
                <th>Signup package</th>
                <th>Join Date</th>
        </tr>
        </thead>
          <tbody>
            <?php
            $level1="select * from member where sponsor=".$this->session->user_id;
             $query1 = $this->db->query($level1);
             foreach($query1->result() as $row1)
               {
                ?>
                <?php
                  $array1= array('id' => $row1->id);
                  $level2="select * from member where sponsor='". $array1['id']."'";
                  $query2 = $this->db->query($level2);
                  foreach($query2->result() as $row2)
                    {
                      $array2= array('id' => $row2->id);
                       $level3="select * from member where sponsor='". $array2['id']."'";
                        $query3 = $this->db->query($level3);
                          foreach($query3->result() as $row3)
                            {
                              $array3= array('id' => $row3->id);
                              $level4="select * from member where sponsor='". $array3['id']."'";
                              $query4 = $this->db->query($level4);
                              foreach($query4->result() as $row4)
                               {
                              $array4= array('id' => $row4->id);
                              $level5="select * from member where sponsor='". $array4['id']."'";
                              $query5 = $this->db->query($level5);
                              foreach($query5->result() as $row5)
                               {
                              $array5= array('id' => $row5->id);
                              $level6="select * from member where sponsor='". $array5['id']."'";
                              $query6 = $this->db->query($level6);
                              foreach($query6->result() as $row6)
                               {
                              $array6= array('id' => $row6->id);
                              $level7="select * from member where sponsor='". $array6['id']."'";
                              $query7 = $this->db->query($level7);
                              foreach($query7->result() as $row7)
                               {
                                 ?>

                                   <tr>
                                     <td><?php echo $row7->name; ?></td>
                                     <td><?php echo  $row7->id; ?></td> 
                                      <td><?php echo $row7->phone; ?></td>
                                     <td><?php echo $row7->sponsor; ?></td>
                                     <td><?php echo $row7->signup_package; ?></td>
                                     <td><?php echo $row7->join_time; ?></td>
                                   </tr>
                                 <?php
                               }
                             }
                           }
                         }
                       }
                     }
                   }
                             ?>
          </tbody>
      </table>
    </div>
  </div>
 <!--  level seven end-->
 <!-- level eight start -->
 <div class="Container">
<h2>level 8 of <?php echo $this->session->user_id; ?></h2>
<div class="table-responsive">
    <table class="table align-items-center table-flush">
        <thead class="thead-light">
        <tr>
                <th>Name</th>
                <th>ID</th>
                <th>Phone</th>
                <th>Sponsor</th>
                <th>Signup package</th>
                <th>Join Date</th>
        </tr>
        </thead>
          <tbody>
            <?php
            $level1="select * from member where sponsor=".$this->session->user_id;
             $query1 = $this->db->query($level1);
             foreach($query1->result() as $row1)
               {
                ?>
                <?php
                  $array1= array('id' => $row1->id);
                  $level2="select * from member where sponsor='". $array1['id']."'";
                  $query2 = $this->db->query($level2);
                  foreach($query2->result() as $row2)
                    {
                      $array2= array('id' => $row2->id);
                       $level3="select * from member where sponsor='". $array2['id']."'";
                        $query3 = $this->db->query($level3);
                          foreach($query3->result() as $row3)
                            {
                              $array3= array('id' => $row3->id);
                              $level4="select * from member where sponsor='". $array3['id']."'";
                              $query4 = $this->db->query($level4);
                              foreach($query4->result() as $row4)
                               {
                              $array4= array('id' => $row4->id);
                              $level5="select * from member where sponsor='". $array4['id']."'";
                              $query5 = $this->db->query($level5);
                              foreach($query5->result() as $row5)
                               {
                              $array5= array('id' => $row5->id);
                              $level6="select * from member where sponsor='". $array5['id']."'";
                              $query6 = $this->db->query($level6);
                              foreach($query6->result() as $row6)
                               {
                              $array6= array('id' => $row6->id);
                              $level7="select * from member where sponsor='". $array6['id']."'";
                              $query7 = $this->db->query($level7);
                              foreach($query7->result() as $row7)
                               {
                                $array7= array('id' => $row7->id);
                              $level8="select * from member where sponsor='". $array8['id']."'";
                              $query8 = $this->db->query($level8);
                              foreach($query8->result() as $row8)
                               {
                                 ?>

                                   <tr>
                                     <td><?php echo $row8->name; ?></td>
                                     <td><?php echo  $row8->id; ?></td> 
                                      <td><?php echo $row8->phone; ?></td>
                                     <td><?php echo $row8->sponsor; ?></td>
                                     <td><?php echo $row8->signup_package; ?></td>
                                     <td><?php echo $row8->join_time; ?></td>
                                   </tr>
                                 <?php
                               }
                             }
                           }
                         }
                       }
                     }
                   }
                 }
                             ?>
          </tbody>
      </table>
    </div>
  </div>

<!--  level eight end -->
<!-- level nine start -->
 <div class="Container">
<h2>level 9 of <?php echo $this->session->user_id; ?></h2>
<div class="table-responsive">
    <table class="table align-items-center table-flush">
        <thead class="thead-light">
        <tr>
                <th>Name</th>
                <th>ID</th>
                <th>Phone</th>
                <th>Sponsor</th>
                <th>Signup package</th>
                <th>Join Date</th>
        </tr>
        </thead>
          <tbody>
            <?php
            $level1="select * from member where sponsor=".$this->session->user_id;
             $query1 = $this->db->query($level1);
             foreach($query1->result() as $row1)
               {
                ?>
                <?php
                  $array1= array('id' => $row1->id);
                  $level2="select * from member where sponsor='". $array1['id']."'";
                  $query2 = $this->db->query($level2);
                  foreach($query2->result() as $row2)
                    {
                      $array2= array('id' => $row2->id);
                       $level3="select * from member where sponsor='". $array2['id']."'";
                        $query3 = $this->db->query($level3);
                          foreach($query3->result() as $row3)
                            {
                              $array3= array('id' => $row3->id);
                              $level4="select * from member where sponsor='". $array3['id']."'";
                              $query4 = $this->db->query($level4);
                              foreach($query4->result() as $row4)
                               {
                              $array4= array('id' => $row4->id);
                              $level5="select * from member where sponsor='". $array4['id']."'";
                              $query5 = $this->db->query($level5);
                              foreach($query5->result() as $row5)
                               {
                              $array5= array('id' => $row5->id);
                              $level6="select * from member where sponsor='". $array5['id']."'";
                              $query6 = $this->db->query($level6);
                              foreach($query6->result() as $row6)
                               {
                              $array6= array('id' => $row6->id);
                              $level7="select * from member where sponsor='". $array6['id']."'";
                              $query7 = $this->db->query($level7);
                              foreach($query7->result() as $row7)
                               {
                              $array7= array('id' => $row7->id);
                              $level8="select * from member where sponsor='". $array8['id']."'";
                              $query8 = $this->db->query($level8);
                              foreach($query8->result() as $row8)
                               {
                              $array8= array('id' => $row8->id);
                              $level9="select * from member where sponsor='". $array9['id']."'";
                              $query9 = $this->db->query($level9);
                              foreach($query9->result() as $row9)
                               {
                                 ?>

                                   <tr>
                                     <td><?php echo $row9->name; ?></td>
                                     <td><?php echo  $row9->id; ?></td> 
                                      <td><?php echo $row9->phone; ?></td>
                                     <td><?php echo $row9->sponsor; ?></td>
                                     <td><?php echo $row9->signup_package; ?></td>
                                     <td><?php echo $row9->join_time; ?></td>
                                   </tr>
                                 <?php
                               }
                             }
                           } 
                         }
                       }
                     }
                   }
                 }
               }
                             ?>
          </tbody>
      </table>
    </div>
  </div>
<!-- level nine end -->
</div>
</div>
  </div>
</div>                    
                        
</div>