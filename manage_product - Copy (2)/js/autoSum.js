/* This script and many more are available free online at
The JavaScript Source!! http://javascript.internet.com
Created by: Jim Stiles | www.jdstiles.com */
function startCalc(){
  interval = setInterval("calc()",1);
}

function calc(){

  one = document.f1.cardrate.value;
  three = document.f1.insertions.value; 
  two = document.f1.adsize.value; 
  document.f1.totalcostcard.value = (one * 1) * (two * 1) * (three * 1);
  
  four = document.f1.totalcostcard.value;
  five = document.f1.discount.value;
  document.f1.discountvalue.value = (four * 1) * (five * 1) /100;
  
  document.f1.netcost.value = (four * 1) - (document.f1.discountvalue.value * 1) ;

  six = document.f1.vat.value;
  document.f1.vatvalue.value = (four * 1) * (six * 1) /100;
  
  seven = document.f1.agency.value;
  document.f1.agencyvalue.value = (document.f1.netcost.value * 1) * (seven * 1) /100;
  
  
  document.f1.totalcost.value = (document.f1.netcost.value * 1) + (document.f1.vatvalue.value * 1) +  (document.f1.agencyvalue.value * 1);
}

function stopCalc(){
  clearInterval(interval);
}

