<?php

if( isset($_SESSION['username']))
{
  header("location:../..homeP.php");
}


?>



   /*     if($AccType=="New")
            {
                    $nom=$_POST['nomP'];
                    $prenom=$_POST['PrenomP'];
                    $CIN=$_POST['CIN'];
                    $Tel=$_POST['Tel'];
                    $AdressP=$_POST['Adr'];
                    $Email=$_POST['Email'];

                    $reqC = "SELECT * from proprietaire where CIN=? ";
                    $statementC=$conn->prepare($reqC);
                    $statementC->bind_param("s",$CIN);
                    $statementC->execute();
                    $resC=$statementC->get_result();
                    $reqT = "SELECT * from proprietaire where Tel=?";
                    $statementT=$conn->prepare($reqT);
                    $statementT->bind_param("s",$Tel);
                    $statementT->execute();
                    $resT=$statementT->get_result();
                    $reqE = "SELECT * from utilisateur where Email=?";
                    $statementE=$conn->prepare($reqE);
                    $statementE->bind_param("s",$Email);
                    $statementE->execute();
                    $resE=$statementE->get_result();


                    if ($resC->num_rows==0 && $resT->num_rows==0 && $resE->num_rows==0)
                     {

                        //creation utilisateur , select Code , creation Proprietaire

                          $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                          $pa = '';
                          for ($i = 0; $i < 8; $i++) {
                              $pa = $pa.$characters[rand(0, strlen($characters))];
                          }
                          $pa=sha1($pa);
                            $type="proprietaire";
                            $reqI = "INSERT INTO `utilisateur`(`username`, `email`, `pass`, `type`) VALUES (?,?,?,?)";
                            $statementI=$conn->prepare($reqI);
                            $statementI->bind_param("ssss",$CIN,$Email,$pa,$type);
                            $statementI->execute();

                            $reqI = "SELECT CodeU FROM utilisateur where username=? ";
                            $statementI=$conn->prepare($reqI);
                            $statementI->bind_param("s",$CIN);
                            $statementI->execute();
                            $resI=$statementI->get_result();
                            $rowI=$resI->fetch_assoc();
                            $CodeU=$rowI['CodeU'];

                            $reqP = "INSERT INTO `proprietaire`(`CodeP`, `CIN`, `adress`, `nom`, `prenom`, `tel`) VALUES (?,?,?,?,?,?)";
                            $statementP=$conn->prepare($reqP);
                            $statementP->bind_param("isssss",$CodeU,$CIN,$AdressP,$nom,$prenom,$Tel);
                            $statementP->execute();

                            $Accval="Ok";
                    }
                    else if($resC->num_rows!=0 && $resT->num_rows!=0)
                    {
                       $alert='<div class="alert alert-danger alert-dismissible fade show" role="alert">
                       <strong>CIN et numero de telephone existent déjà:</strong> entrez des nouvelles valeures.
                       <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                       <span aria-hidden="true">&times;</span>
                       </button>
                       </div>';
                    }
                    else if($resT->num_rows!=0 && $resE->num_rows!=0)
                    {
                        $alert='<div class="alert alert-danger alert-dismissible fade show" role="alert">
                       <strong>Email et numero de telephone existent déjà:</strong> entrez des nouvelles valeures.
                       <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                       <span aria-hidden="true">&times;</span>
                       </button>
                       </div>';
                    }
                    else if($resE->num_rows!=0 && $resC->num_rows!=0)
                    {
                        $alert='<div class="alert alert-danger alert-dismissible fade show" role="alert">
                       <strong>CIN et Email existent déjà :</strong> entrez des nouvelles valeures.
                       <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                       <span aria-hidden="true">&times;</span>
                       </button>
                       </div>';
                    }
                    else if($resC->num_rows!=0 && $resT->num_rows!=0 && $resE->num_rows!=0)
                    {
                       $alert='<div class="alert alert-danger alert-dismissible fade show" role="alert">
                       <strong>CIN,Email et numero de telephone existent déjà :</strong> entrez des nouvelles valeures.
                       <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                       <span aria-hidden="true">&times;</span>
                       </button>
                       </div>';
                    }
                    else if ($resC->num_rows!=0)
                    {
                      $alert='<div class="alert alert-danger alert-dismissible fade show" role="alert">
                      <strong>CIN existe déjà :</strong> entrez une nouvelle valeure.
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                      </button>
                      </div>';
                    }
                    else if ($resT->num_rows!=0)
                    {
                      $alert='<div class="alert alert-danger alert-dismissible fade show" role="alert">
                      <strong>Numero de Tele existe déjà:</strong> entrez une nouvelle valeure.
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                      </button>
                      </div>';
                    }
                    else if ($resE->num_rows!=0)
                    {
                       $alert='<div class="alert alert-danger alert-dismissible fade show" role="alert">
                       <strong>Email existe déjà :</strong> entrez une nouvelle valeure.
                       <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                       <span aria-hidden="true">&times;</span>
                       </button>
                       </div>';
                    }
            }
            else if($AccType="EXST")
            {

                    $Username=$_POST['Username'];

                    $reqU = "SELECT * from utilisateur where Username=? ";
                    $statementU=$conn->prepare($reqU);
                    $statementU->bind_param("s",$Username);
                    $statementU->execute();
                    $resU=$statementU->get_result();
                    $rowU=$resU->fetch_assoc();

                    if ($resU->num_rows==1)
                    {
                        $Utype=$rowU['type'];
                        if($Utype=="normal")
                        {
                        $CodeU=$rowU['CodeU'];
                        $req = "INSERT INTO `proprietaire`(`CodeP`) values() ";
                        $statement=$conn->prepare($req);
                        $statement->bind_param("i",$CodeU);
                        $statement->execute();
                        $Accval="Ok";               
                        }
                        else if ($Utype=="proprietaire"){
                        $Accval="Ok";
                        $CodeU=$rowU['CodeU'];
                        }else
                        {
                        $alert='<div class="alert alert-danger alert-dismissible fade show" role="alert">
                       <strong>ce username appartien à un admin :</strong> tapez un autre username.
                       <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                       <span aria-hidden="true">&times;</span>
                       </button>
                       </div>';
                        }
                    }else if($resU->num_rows!=1)
                        {
                        $alert='<div class="alert alert-danger alert-dismissible fade show" role="alert">
                       <strong>Username non existant :</strong> entrez un unsername valide.
                       <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                       <span aria-hidden="true">&times;</span>
                       </button>
                       </div>';
                        }
                        
            }
*/