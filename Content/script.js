//Pathogen Collab
//Copyright © 2010 by Pathogen Studios
//http://www.pathogenstudios.com/

function clearInputBox(This)//,passwordbox)
{
 This.style.color = "black";
 This.value="";
 This.onfocus=null;
 /*//This was a great idea but does not work in IE8 even.
 if (passwordbox)
 {
  This.type="password";
  This.focus();//Changing the type causes focus to be lost (in Opera anyway.)
 }*/
}

///////////Animations
//Flash animation:
var animation_flashStep = 0.0;
function animation_flashDo()
{
 animation_flashItems = document.getElementsByName("animation_flash");
 animation_flashVal = (Math.sin(animation_flashStep)+1.0)/2.0;
 for(i=0;i<animation_flashItems.length;i++)
 {
  animation_flashItems[i].style.opacity=animation_flashVal;
 }
 animation_flashStep+=0.05;
 
 setTimeout("animation_flashDo()",10);
}

//Inits:
setTimeout("animation_flashDo()",10);

function copy(textData)
{
 var flashcopier = 'flashcopier';
 if(!document.getElementById(flashcopier))
 {
  var divholder = document.createElement('div');
  divholder.id = flashcopier;
  document.body.appendChild(divholder);
 }
 document.getElementById(flashcopier).innerHTML = '';
 var divinfo = '<embed src="Content/_clipboard.swf" FlashVars="clipboard='+encodeURIComponent(textData)+'" width="0" height="0" type="application/x-shockwave-flash"></embed>';
 document.getElementById(flashcopier).innerHTML = divinfo;
}

function swapOnce(id1,id2,This)
{
 document.getElementById(id1).style.display="none";
 document.getElementById(id2).style.display="block";
 This.style.display="none";
}

/*Countdown Timers:

Timers are stored in a multi-dimensional array which each row represents a timer and the columns represent:
[name][id][days][hours][minutes][seconds][done]
*/
var countdowns = [];
function addCountdown(name,id,days,hours,minutes,seconds) {countdowns[countdowns.length] = [name,id,days,hours,minutes,seconds,false];}
function tickCountdown()
{
 for(i=0;i<countdowns.length;i++)
 {
  //Tick:
  if (!countdowns[i][6])
  {
   countdowns[i][5]--;
   if (countdowns[i][5]<0)
   {
    countdowns[i][5]=59;
    countdowns[i][4]--;
    if (countdowns[i][4]<0)
    {
     countdowns[i][4]=59;
     countdowns[i][3]--;
     if (countdowns[i][3]<0)
     {
      countdowns[i][3]=23;
      countdowns[i][2]--;
      if (countdowns[i][2]<0)
      {
       countdowns[i][2]=countdowns[i][3]=countdowns[i][4]=countdowns[i][5]=0;
       countdowns[i][6]=true;
      }
     }
    }
   }
  }
  
  //Printout:
  name = countdowns[i][0];
  id = countdowns[i][1];
  days = countdowns[i][2];
  hours = countdowns[i][3];
  minutes = countdowns[i][4];
  seconds = countdowns[i][5];
  newValue = days+' day'+(days==1?'':'s')+', '+hours+' hour'+(hours==1?'':'s')+', '+minutes+' minute'+(minutes==1?'':'s')+', and '+seconds+' second'+(seconds==1?'':'s')+' until '+name+'!';
  document.getElementById(id).innerHTML=newValue;
 }
 setTimeout("tickCountdown()",1000);
}
function startCountdowns()
{
 setTimeout("tickCountdown()",1000);
}