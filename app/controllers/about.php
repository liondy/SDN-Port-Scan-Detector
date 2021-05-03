<?php 
  class About{
    public function index($nama='Michael',$umur=22){
      echo "Halo, nama saya $nama, umur saya $umur";
    }

    public function page(){
      echo 'About::page';
    }
  }
