<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="UTF-8">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="author" content="Fabio Zendhi Nagao" />
		<meta name="editor" content="Notepad++" />
		<meta name="robots" content="index, follow" />
		<meta name="revisit-after" content="15 Days" />
		<link rel="stylesheet" type="text/css" href="/common/css/reset-fonts.css" />
		<title>iMask - An open source (free) javascript tool for creating input and textarea masking</title>
		<meta name="description" content="iMask is an open source (free) javascript tool for creating input and textarea masking." />
		<meta name="keywords" content="iMask, mask, form mask, input mask, text mask, javascript mask, moo.fx, mootools, plugin, input format, text format, unobtrusive JavaScript, Fabio Zendhi Nagao" />
		<!--//<script type="text/javascript" src="/common/js/mootools.v1.00.js"></script>//-->
		<script type="text/javascript" src="js/mootools.js"></script>
		<script type="text/javascript" src="js/imask.js"></script>
		<script type="text/javascript">
			function cargaimask(){
				
					new iMask({
						onFocus: function(obj) {
							obj.setStyles({"background-color":"#ff8", border:"1px solid #880"});
						},

						onBlur: function(obj) {
							obj.setStyles({"background-color":"#fff", border:"1px solid #ccc"});
						},

						onValid: function(event, obj) {
							obj.setStyles({"background-color":"#8f8", border:"1px solid #080"});
						},

						onInvalid: function(event, obj) {
							if(!event.shift) {
								obj.setStyles({"background-color":"#f88", border:"1px solid #800"});
							}
						}
					});
			};
		</script>
	</head>
	<body onload="cargaimask()">
		<div id="container">
			<div id="container_hd"></div>
			<div id="container_bd">


<h1>iMask</h1>
<p>by Fabio Zendhi Nagao (<a href="http://zend.lojcomm.com.br">http://zend.lojcomm.com.br</a>)</p>
<p>iMask is an open source (free) javascript tool for creating input and textarea masking.</p>
<h2><a id="selflink_index">index:</a></h2>
<ol id="index_list">
	<li><a href="#selflink_index">Index</a></li>
	<li><a href="#selflink_abstract">Abstract</a></li>
	<li><a href="#selflink_examples">Examples</a></li>
	<li><a href="#selflink_browserCompatibility">Browser compatibility</a></li>
	<li><a href="#selflink_features">Features</a></li>
	<li><a href="#selflink_usage">Usage</a></li>
	<li><a href="#selflink_license">License</a></li>
	<li><a href="#selflink_download">Download</a></li>
	<li><a href="#selflink_versionHistory">Version history</a></li>
	<li><a href="#selflink_comments">Comments</a></li>
</ol>
<h2><a id="selflink_abstract">abstract:</a></h2>
<p>Who haven't ever wanted to apply an input mask to an HTML form field? This very common feature in traditional GUI applications is not natively supported by web applications. iMask goal is to implement an easy way for developers to add mask into their form fields, increasing the database and software consistency with standard compliant XHTML and unobtrusive JavaScript.</p>
<h2><a id="selflink_examples">examples:</a></h2>
<form action="#">
	<table>
		<tr>
			<td><label>ID:</label></td>
			<td><input class="iMask" id="myId" name="myId" type="text"
				value="15357595X"
				alt="{
					type:'fixed',
					mask:'[[99.999.999-x]]',
					stripMask: true
				}"
			/></td>
		</tr>
		<tr>
			<td><label>Phone:</label></td>
			<td><input class="iMask" id="myPhone" name="myPhone" type="text"
				value="116969"
				alt="{
					type:'fixed',
					mask:'(99) 9999-9999',
					stripMask: true
				}"
			/></td>
		</tr>
		<tr>
			<td><label>Code:</label></td>
			<td><input class="iMask" id="myCode" name="myCode" type="text"
				value="76543-210"
				alt="{
					type:'fixed',
					mask:'99999-999',
					stripMask: false
				}"
			/></td>
		</tr>
		<tr>
			<td><label>Money:</label></td>
			<td><input class="iMask" id="myMoney" name="myMoney" type="text"
				value="0.09"
				alt="{
					type:'number',
					groupSymbol: ',',
					groupDigits: 3,
					decSymbol: '.',
					decDigits: 2,
					stripMask: false
				}"
			/></td>
		</tr>
	</table>
</form>
<h2><a id="selflink_browserCompatibility">browser compatibility:</a></h2>
<p>iMask is compatible and tested in <a href="http://www.microsoft.com/ie/">Internet Explorer</a>, <a href="http://mozilla.com/">Firefox</a> (and its mozilla friends), <a href="http://opera.com/">Opera</a> and <a href="http://apple.com/safari/">Safari</a> (thanks macspyre for testing it). But it should work in other browsers too... If you successfully find another compatible browser, please let me know ;)</p>
<h2><a id="selflink_features">features:</a></h2>
<table id="features_table">
	<tr>
		<td class="lft">
			<h3>1. keyboard control</h3>
			<img src="images/specialkeys.gif" alt="Special keys functions" />
		</td>
		<td class="rgt">
			<h3>2. dynamic fixed mask definition</h3>
			<p>Create your own masks for type "fixed" using [9, a, x] notation:</p>
			<p>9 := numerical symbols.<br />Default set is: "123456789"</p>
			<p>A := alphabetical symbols.<br />Default set is: "abcdefghijklmnopqrstuvwxyz"</p>
			<p>X := alphanumerical symbols.<br />Default set is both alphabetical and numerical symbols.</p>
		</td>
	</tr>
	<tr>
		<td class="lft">
			<h3>3. event handling</h3>
			<p>Integrate with other components and create rich interactive interfaces. iMask class can be initialized with the following customizable methods:</p>
			<ul>
				<li>onFocus - fires when field get focus</li>
				<li>onBlur - fires when field lost focus</li>
				<li>onValid - fires when user press a valid char</li>
				<li>onInvalid - fires when user press a invalid char</li>
				<li>onKeyDown - fires at every key press</li>
			</ul>
		</td>
		<td class="rgt">
			<h3>4. dynamically charset definition</h3>
			<p>Want to extend the default charset for a card? [9, a, x]</p>
			<p>Initialize iMask with your own set of validNumbers, validAlphas and validAlphaNums!</p>
		</td>
	</tr>
</table>
<h2><a id="selflink_usage">how to use:</a></h2>
<p>
	First of all, iMask is built over MooTools v1.1, so both libraries are required.<br />
	Get MooTools at <a href="http://mootools.net">http://mootools.net</a> and iMask <a href="#selflink_download">here</a>.<br />
	With scripts in hands, include them between your "head" definition:
</p>
<form action="#">
	<div><textarea rows="" cols="" name="usage_include" class="xhtml">
&lt;head>
.
. <!--// codes here //-->
.
	&lt;script type="text/javascript" src="js/mootools.js">&lt;/script>
	&lt;script type="text/javascript" src="js/imask.js">&lt;/script>
.
. <!--// and here //-->
.
&lt;/head>
				</textarea></div>
				<p>Initialize the iMask class:</p>
				<div><textarea rows="" cols="" name="usage_initialize" class="javascript">
new iMask({
	onFocus: function(obj) {
		obj.setStyles({"background-color":"#ff8", border:"1px solid #880"});
	},

	onBlur: function(obj) {
		obj.setStyles({"background-color":"#fff", border:"1px solid #ccc"});
	},

	onValid: function(event, obj) {
		obj.setStyles({"background-color":"#8f8", border:"1px solid #080"});
	},

	onInvalid: function(event, obj) {
		if(!event.shift) {
			obj.setStyles({"background-color":"#f88", border:"1px solid #800"});
		}
	}
});
	</textarea></div>
	<p>That's it, at this rate you should be able to use iMask. To enable a mask for a field, just add the properties class="iMask" and alt="&lt;options>".</p>
	<p>&lt;options> is a string representing an object in JavaScript Object Notation (aka. JSON). Here is the list of valid properties:</p>
	<ul>
		<li><b>type</b> : (string) fixed || number.</li>
		<li><b>mask</b> : (string) your mask using [ 9, a, x ] notation.</li>
		<li><b>stripMask</b> : (boolean) true || false.</li>
	</ul>
	<p>Nothing better than an example to explain a tool usage, so here is the code behind this page examples:</p>
	<div><textarea rows="" cols="" name="usage_xhtml" class="xhtml">
&lt;table>
	&lt;tr>
		&lt;td>&lt;label>ID:&lt;/label>&lt;/td>
		&lt;td>&lt;input class="iMask" id="myId" name="myId" type="text"
			value="15357595X"
			alt="{
				type:'fixed',
				mask:'[99.999.999-x]',
				stripMask: true
			}"
		/>&lt;/td>
	&lt;/tr>
	&lt;tr>
		&lt;td>&lt;label>Phone:&lt;/label>&lt;/td>
		&lt;td>&lt;input class="iMask" id="myPhone" name="myPhone" type="text"
			value="116969"
			alt="{
				type:'fixed',
				mask:'(99) 9999-9999',
				stripMask: true
			}"
		/>&lt;/td>
	&lt;/tr>
	&lt;tr>
		&lt;td>&lt;label>Code:&lt;/label>&lt;/td>
		&lt;td>&lt;input class="iMask" id="myCode" name="myCode" type="text"
			value="76543-210"
			alt="{
				type:'fixed',
				mask:'99999-999',
				stripMask: false
			}"
		/>&lt;/td>
	&lt;/tr>
	&lt;tr>
		&lt;td>&lt;label>Money:&lt;/label>&lt;/td>
		&lt;td>&lt;input class="iMask" id="myMoney" name="myMoney" type="text"
			value="0.09"
			alt="{
				type:'number',
				groupSymbol: ',',
				groupDigits: 3,
				decSymbol: '.',
				decDigits: 2,
				stripMask: false
			}"
		/>&lt;/td>
	&lt;/tr>
&lt;/table>
	</textarea></div>
</form>
<h2><a id="selflink_license">license:</a></h2>
<p>This piece of code is is released under the Open Source MIT license, which permits you to use it and modify it in every circumstance. For more details read it below:</p>
<div id="license">
	<h3>The MIT License</h3>
	<p>Copyright (c) 2007 Fabio Zendhi Nagao - http://zend.lojcomm.com.br</p>
	<p>Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:</p>
	<p>The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.</p>
	<p>THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.</p>
</div>
<h2><a id="selflink_download">download:</a></h2>
<p>iMask is available in the formats below:</p>
<ul>
	<li><a href="js/iMask.js">packed version</a> : Ultra compressed version (5.39KB) - The ready to use "as it is" version.</li>
	<li><a href="js/iMask-full.js">full version</a> : Full version (12.6KB) - For developers who needs to change or just study it.</li>
</ul>
<h2><a id="selflink_versionHistory">version history:</a></h2>
<p>05/22/2007 : v1.0 - first public release</p>
<p>03/16/2007 : v0.002</p>
<ul>
	<li>[changed] totally re-built version. iMask is now a Class instead of an Object</li>
	<li>[changed] hence it's a class, it needs a small javascript knowledge to initialize it</li>
	<li>[added] enabled tabbing to previous field @shift+tab</li>
	<li>[added] onFocus, onBlur, onKeyDown, onInvalid event handlers</li>
	<li>[fixed] enabled case sensitivity</li>
</ul>
<p>03/11/2007 : v0.001</p>
<ul>
	<li>[added] enabled form submition @return</li>
	<li>[added] enabled tabbing between fields @tab</li>
	<li>[fixed] there's no longer any javascript knowledge to initialize it</li>
	<li>[fixed] iMask.stopEvent() -> event.stop() (thanks to Valerio)</li>
</ul>
<h2><a id="selflink_comments">comments:</a></h2>
<div id="mod_comments">
<div id="mod_comments_list">
	<div class="mod_comments_list_paging">
<ul>
	<li class="mod_comments_list_view_all"><a href="#" onclick="mod_comments_list_page(0); return false;" onmouseover="window.status='View All';return true;" onmouseout="window.status='';return true;">View All</a></li>
	<li class="mod_comments_list_number">1</li>
	<li class="mod_comments_list_number"><a href="#" onclick="mod_comments_list_page(2); return false;" onmouseover="window.status=' Go to 2';return true;" onmouseout="window.status='';return true;">2</a></li>
	<li class="mod_comments_list_number"><a href="#" onclick="mod_comments_list_page(3); return false;" onmouseover="window.status=' Go to 3';return true;" onmouseout="window.status='';return true;">3</a></li>
	<li class="mod_comments_list_next"><a href="#" onclick="mod_comments_list_page(2); return false;" onmouseover="window.status=' Next';return true;" onmouseout="window.status='';return true;"> Next</a></li>
</ul>
	</div>
	<div class="mod_comments_list even">
		<div><img src="/common/images/stars_5.gif" alt="User rating" /><span class="mod_comments_list_title">First release</span><span class="mod_comments_list_date">3/10/2007 2:06:20 AM</span></div>
		<div><span>Author:</span><span class="mod_comments_list_author">Fabio Zendhi Nagao</span><span class="mod_comments_list_website">(http://zend.lojcomm.com.br/)</span>
</div>
		<div><span class="mod_comments_list_comment">After a lot of research and documentation (the worst part oO;)
<br />I'm proud to make public this first version of iMask. I hope it turns our developers life easier.</span></div>
	</div>
	<div class="mod_comments_list odd">
		<div><img src="/common/images/stars_5.gif" alt="User rating" /><span class="mod_comments_list_title">Nice indeed</span><span class="mod_comments_list_date">3/21/2007 5:29:13 PM</span></div>
		<div><span>Author:</span><span class="mod_comments_list_author">bjorn</span></div>
		<div><span class="mod_comments_list_comment">Hi! really nice stuff, i will defenetley use it in a future site of mine... Got a nother question to ya. You smoothscroll. Is there by any chance a possebility for you to show me or explain how you made that? From what i know you might have gone from mootools? like that bumpy effect on the end =) Thx for sharing!</span></div>
	</div>
	<div class="mod_comments_list even">
		<div><img src="/common/images/stars_5.gif" alt="User rating" /><span class="mod_comments_list_title">Not working ...</span><span class="mod_comments_list_date">3/23/2007 7:10:41 AM</span></div>
		<div><span>Author:</span><span class="mod_comments_list_author">Peter PRESKA</span></div>
		<div><span class="mod_comments_list_comment">Hi Fabio,<br />I am trying to use your script in my form fields but getting this error<br />from Javascript console:<br /><br />this.setOptions is not a function<br />initialize(Object)imask.js (line 43)<br />e()mootools.js (line 2)<br />[Break on this error] this.setOptions(this.defaultOptions(), options);</span></div>
	</div>
	<div class="mod_comments_list odd">
		<div><img src="/common/images/stars_5.gif" alt="User rating" /><span class="mod_comments_list_title">Great lib</span><span class="mod_comments_list_date">3/26/2007 2:45:26 PM</span></div>
		<div><span>Author:</span><span class="mod_comments_list_author">Ronie Uliana</span></div>
		<div><span class="mod_comments_list_comment">I have found no one better than this!<br /><br />Awesome! Keep the good work! :)</span></div>
	</div>
	<div class="mod_comments_list even">
		<div><img src="/common/images/stars_5.gif" alt="User rating" /><span class="mod_comments_list_title">Great!</span><span class="mod_comments_list_date">4/16/2007 10:33:34 AM</span></div>
		<div><span>Author:</span><span class="mod_comments_list_author">Leandro Almeida</span></div>
		<div><span class="mod_comments_list_comment">It seems a great script. But i dont like one thing: the backspace should behave as del key, removing the characters...
<br />
<br />Is there a way to alter it?
<br />
<br />Valeu cara, bom trabalho!</span></div>
	</div>
	<div class="mod_comments_list odd">
		<div><img src="/common/images/stars_5.gif" alt="User rating" /><span class="mod_comments_list_title">Great work.. but...</span><span class="mod_comments_list_date">4/21/2007 10:28:17 PM</span></div>
		<div><span>Author:</span><span class="mod_comments_list_author">Adam</span></div>
		<div><span class="mod_comments_list_comment">Hi there, Great stuff.... however I have a problem when I set stripMask: false - I can no longer enter any data.. this is being tested on mac FF2.</span></div>
	</div>
	<div class="mod_comments_list even">
		<div><img src="/common/images/stars_5.gif" alt="User rating" /><span class="mod_comments_list_title">Two lil' things..</span><span class="mod_comments_list_date">5/6/2007 2:07:13 PM</span></div>
		<div><span>Author:</span><span class="mod_comments_list_author">Steve</span><span class="mod_comments_list_website">(http://inscrutable-exhortations.com)</span>
</div>
		<div><span class="mod_comments_list_comment">Any way you can add in a handler for shift+tab to move backwards between fields? It works with normal forms, and is nice when you want to go back.. More useful because of the second suggestion:<br /><br />Auto-tab. Would be a great addition.<br /><br />Aside from those, and even without them, this is gold man, pure gold. </span><span class="mod_comments_list_answer">Hi Steve,
<br />
<br />As you can see in the version history, your suggestions are already implemented:
<br />@v1.001 [added] enabled tabbing between fields @tab
<br />@v1.002 [added] enabled tabbing to previous field @shift+tab
<br />
<br />Please, if they are not working for you, let me know your config.
<br />
<br />Best Regards, Fabio Zendhi Nagao @ 05/07/2007 0h18</span></div>
	</div>
	<div class="mod_comments_list odd">
		<div><img src="/common/images/stars_5.gif" alt="User rating" /><span class="mod_comments_list_title">Nice but,</span><span class="mod_comments_list_date">5/23/2007 6:49:20 AM</span></div>
		<div><span>Author:</span><span class="mod_comments_list_author">Patrick</span><span class="mod_comments_list_website">(nositeyet)</span>
</div>
		<div><span class="mod_comments_list_comment">Nice but what i seem to be missing is a max-length on the money field, it totally ignores the input maxlength.</span></div>
	</div>
	<div class="mod_comments_list even">
		<div><img src="/common/images/stars_5.gif" alt="User rating" /><span class="mod_comments_list_title">Exactly what I am looking for</span><span class="mod_comments_list_date">5/26/2007 1:14:51 AM</span></div>
		<div><span>Author:</span><span class="mod_comments_list_author">Siva</span></div>
		<div><span class="mod_comments_list_comment">Excellent Job.<br /><br />This is exactly what I am looking for.  Linking this fValidator makes the web-development easy and fun.<br /><br />Appreciate your efforts and time.<br /><br />Simple thank you is not a measure for your efforts.<br /><br />Cheers<br />Siva</span></div>
	</div>
	<div class="mod_comments_list odd">
		<div><img src="/common/images/stars_5.gif" alt="User rating" /><span class="mod_comments_list_title">2 things missing</span><span class="mod_comments_list_date">5/31/2007 9:58:09 AM</span></div>
		<div><span>Author:</span><span class="mod_comments_list_author">nehnard</span></div>
		<div><span class="mod_comments_list_comment">This great script has two missing features.
<br />1. negative numbers 
<br />- sign is not supported for type:'number'
<br />2. copy paste to/from clipboard</span><span class="mod_comments_list_answer">Yes, it's true... i've not solved the clipboard problem yet, I saw some approachs from the other mootools developers though... i'll be updating as soon i can.</span></div>
	</div>
	<div class="mod_comments_list even">
		<div><img src="/common/images/stars_5.gif" alt="User rating" /><span class="mod_comments_list_title">Great start</span><span class="mod_comments_list_date">6/4/2007 6:56:04 AM</span></div>
		<div><span>Author:</span><span class="mod_comments_list_author">ishtov</span><span class="mod_comments_list_website">(http://www.theKehila.com)</span>
</div>
		<div><span class="mod_comments_list_comment">Looks like a great script.<br />Until now have been using Adobe Spry together with Mootools, which just just hurts so much.  While A. Newton does has a form validator, this is what we need!  <br />I really admire you guys that write these stuff - I have enough troubles just getting it to work. :)<br />Looking forward to updates (perhaps you can rip the ideas - but not the verbose code - off of spry?):<br />A way to make the backspace key erase - curent method is confusing.<br />The ability to highlight the whole string and copy it.<br />The ability to select any character by cliking it.<br />A blinking cursor [even a fake one by using the pipe with .periodical()]<br />Why is it showing me the brackets in the first example (I do not understand, must be feature).<br />Gee whiz, I ought to play with this a bit more before posting. Am using FF1.5 on XP.<br />Thanks though.<br /><br /></span><span class="mod_comments_list_answer">Thanks for your suggestions! Wait for the next release xD</span></div>
	</div>
	<div class="mod_comments_list odd">
		<div><img src="/common/images/stars_5.gif" alt="User rating" /><span class="mod_comments_list_title">Opera</span><span class="mod_comments_list_date">6/7/2007 6:48:47 AM</span></div>
		<div><span>Author:</span><span class="mod_comments_list_author">Peter</span><span class="mod_comments_list_website">(kikoz.ru)</span>
</div>
		<div><span class="mod_comments_list_comment">In Opera I can delete characters and mask goes wrong - these characters places gets removed and never come back.</span><span class="mod_comments_list_answer">Hi, it's true. Somehow Opera delete event behaves in a way that I'm not able to stop it's event from propagating. If you have an idea on how to handle it, please let me know.</span></div>
	</div>
	<div class="mod_comments_list even">
		<div><img src="/common/images/stars_5.gif" alt="User rating" /><span class="mod_comments_list_title">Tabbing bug</span><span class="mod_comments_list_date">6/11/2007 2:40:04 PM</span></div>
		<div><span>Author:</span><span class="mod_comments_list_author">Bruno Daniel</span><span class="mod_comments_list_website">(www.vault7.com)</span>
</div>
		<div><span class="mod_comments_list_comment">I found that when you hold tab to quickly navigate through all the fields in a form, and two or more iMask-enabled fields are in the way, they'll swap focus in a infinite loop...<br /><br />Anyway, thanks for this great tool, I'm using it in in some of my projects :D</span></div>
	</div>
	<div class="mod_comments_list odd">
		<div><img src="/common/images/stars_5.gif" alt="User rating" /><span class="mod_comments_list_title">Can't enter data if stripMask=false and no initial value</span><span class="mod_comments_list_date">6/27/2007 5:44:54 PM</span></div>
		<div><span>Author:</span><span class="mod_comments_list_author">Leandro</span><span class="mod_comments_list_website">(scrum.com.br)</span>
</div>
		<div><span class="mod_comments_list_comment">Hi F&#195;&#161;bio,<br /><br />Congrats, this is great work!<br /><br />I'm testing with dates and I can't enter any data when stripMask='false' and the field has no initial value. Is this a bug/feature? <br /><br />If you change stripMask to true, then it works, but it has an awkward effect on dates when the field looses focus (from: 01/01/2001 to: 01012001).<br /><br />Cheers.</span></div>
	</div>
	<div class="mod_comments_list even">
		<div><img src="/common/images/stars_5.gif" alt="User rating" /><span class="mod_comments_list_title">using iMask with Ajax</span><span class="mod_comments_list_date">6/27/2007 6:30:17 PM</span></div>
		<div><span>Author:</span><span class="mod_comments_list_author">Leandro</span><span class="mod_comments_list_website">(scrum.com.br)</span>
</div>
		<div><span class="mod_comments_list_comment">I'm trying to use iMask with fields loaded dynamically using ajax. <br />The problem, which I guess is expected, is that the fields are not &quot;parsed&quot; or &quot;initialized&quot; by iMask, because they don't event exist when iMask was created. <br /><br />Is there any way I can &quot;add&quot; fields to iMask after it's creation?<br /><br />Cheers!</span></div>
	</div>
	<div class="mod_comments_list odd">
		<div><img src="/common/images/stars_3.gif" alt="User rating" /><span class="mod_comments_list_title">Stripmask</span><span class="mod_comments_list_date">7/18/2007 10:21:18 AM</span></div>
		<div><span>Author:</span><span class="mod_comments_list_author">Alexander</span></div>
		<div><span class="mod_comments_list_comment">At first I thought this iMask thing is great, but I run into a nasty bug. When I set stripMask to false, the entire thing doesn't work anymore. I tested this on IE6.</span></div>
	</div>
	<div class="mod_comments_list even">
		<div><img src="/common/images/stars_5.gif" alt="User rating" /><span class="mod_comments_list_title">stripMask</span><span class="mod_comments_list_date">7/27/2007 2:30:40 PM</span></div>
		<div><span>Author:</span><span class="mod_comments_list_author">Johnny</span></div>
		<div><span class="mod_comments_list_comment">Hey man, vou escrever em pt mesmo hein...<br /><br />Obrigado por suas contribui&#195;&#167;&#195;&#181;es, bom gostaria de sugerir algo, penso que poderia ter mais um par&#195;&#162;metro para deixar o stripMask:true e no onBlur o campo permaner formatado com a m&#195;&#161;scara.<br /><br />E estou com outro problema que me parece um bug, qdo utilizo {type:'fixed',mask:'999.999.999-99',stripMask: false} &#195;&#169; obrigat&#195;&#179;rio o campo ter um value inicial?<br /><br />Abra&#195;&#167;o.</span></div>
	</div>
	<div class="mod_comments_list odd">
		<div><img src="/common/images/stars_5.gif" alt="User rating" /><span class="mod_comments_list_title">A great job</span><span class="mod_comments_list_date">7/30/2007 3:21:30 PM</span></div>
		<div><span>Author:</span><span class="mod_comments_list_author">Juan Talavera</span><span class="mod_comments_list_website">(www.netvision.com.py)</span>
</div>
		<div><span class="mod_comments_list_comment">finally a serious script for this stuff. thx.</span></div>
	</div>
	<div class="mod_comments_list even">
		<div><img src="/common/images/stars_5.gif" alt="User rating" /><span class="mod_comments_list_title">Tabbing Bug</span><span class="mod_comments_list_date">8/2/2007 2:50:35 PM</span></div>
		<div><span>Author:</span><span class="mod_comments_list_author">Skidank</span></div>
		<div><span class="mod_comments_list_comment">I can also confirm there appears to be some sort of tabbing bug in both IE7 and Firefox 2.  I think the bug happens when tabbing between masked textfields and mouse clicking to another masked textfield at the same time in an out of tab order sequence.  The javascript keeps switching focus between textfields indefinitely and doesn't allow the user to gain back control until the user reloads the page or switches to a different tabbed webpage.
<br />
<br />Besides that one bug, the masked library works great.  </span></div>
	</div>
	<div class="mod_comments_list odd">
		<div><img src="/common/images/stars_5.gif" alt="User rating" /><span class="mod_comments_list_title">Copy &amp; Paste</span><span class="mod_comments_list_date">8/4/2007 9:12:53 PM</span></div>
		<div><span>Author:</span><span class="mod_comments_list_author">Andr&#195;&#169; Fiedler</span><span class="mod_comments_list_website">(www.visualdrugs.net)</span>
</div>
		<div><span class="mod_comments_list_comment">is it possible to implement past? I mean, to fetch the pastet string and try to input it.. char by char? Copy would also be nice, with added sepparators (if used). ;o)</span></div>
	</div>
	<div class="mod_comments_list_paging">
<ul>
	<li class="mod_comments_list_view_all"><a href="#" onclick="mod_comments_list_page(0); return false;" onmouseover="window.status='View All';return true;" onmouseout="window.status='';return true;">View All</a></li>
	<li class="mod_comments_list_number">1</li>
	<li class="mod_comments_list_number"><a href="#" onclick="mod_comments_list_page(2); return false;" onmouseover="window.status=' Go to 2';return true;" onmouseout="window.status='';return true;">2</a></li>
	<li class="mod_comments_list_number"><a href="#" onclick="mod_comments_list_page(3); return false;" onmouseover="window.status=' Go to 3';return true;" onmouseout="window.status='';return true;">3</a></li>
	<li class="mod_comments_list_next"><a href="#" onclick="mod_comments_list_page(2); return false;" onmouseover="window.status=' Next';return true;" onmouseout="window.status='';return true;"> Next</a></li>
</ul>
	</div>
</div>
<form id="mod_comments_form" action="/common/comments/insert.asp">
	<p>Did you like it? Disliked it? Express your feelings, leave-me a message. (Yellow fields are required)</p>
	<table>
		<tr>
			<td><label for="mod_comments_form_name">name:</label></td>
			<td colspan="3"><input type="text" class="required" id="mod_comments_form_name" name="name" maxlength="100" /></td>
		</tr>
		<tr>
			<td><label for="mod_comments_form_email">email:</label></td>
			<td><input type="text" id="mod_comments_form_email" name="email" maxlength="100" /></td>
			<td><label for="mod_comments_form_website">website:</label></td>
			<td><input type="text" id="mod_comments_form_website" name="website" maxlength="255" /></td>
		</tr>
		<tr>
			<td colspan="4" style="padding-top:10px;"><label for="mod_comments_form_comment">comment:</label></td>
		</tr>
		<tr>
			<td><label for="mod_comments_form_title">title:</label></td>
			<td><input type="text" class="required" id="mod_comments_form_title" name="title" maxlength="255" /></td>
			<td><label for="mod_comments_form_rating">rating:</label></td>
			<td><select class="required" id="mod_comments_form_rating" name="rating">
				<option value="5">5</option>
				<option value="4">4</option>
				<option value="3">3</option>
				<option value="2">2</option>
				<option value="1">1</option>
			</select></td>
		</tr>
		<tr>
			<td colspan="4"><textarea id="smartField" name="smartField"></textarea><textarea class="required" id="mod_comments_form_comment" name="comment" rows="" cols="" style="width:100%; height:160px; border:1px solid #ccc"></textarea></td>
		</tr>
		<tr>
			<td colspan="4"><input type="hidden" name="idset" value="imask" /><input type="button" style="padding:0 10px; background:#ccc; font-family:Arial; color:#fff;" value="submit" onclick="mod_comments_form_submit()" /></td>
		</tr>
	</table>
</form>
<script type="text/javascript">
//<![CDATA[

function mod_comments_list_page(p) {
	new Ajax("/common/comments/list.asp?idset=imask&p="+ p, {
		method: "get",
		update: "mod_comments_list"
	}).request();
}

function mod_comments_form_submit() {
	var f = $("mod_comments_form_name");
	if(f.value == "") {alert("Name is required.");f.focus();return;}
	f = $("mod_comments_form_title");
	if(f.value == "") {alert("Title is required.");f.focus();return;}
	f = $("mod_comments_form_comment");
	if(f.value == "") {alert("Comment is required.");f.focus();return;}

	MOOdalBox.open("/common/comments/insert.asp", "System message", "moodalbox 300 80", $("mod_comments_form"));
}

//]]>
</script>
</div>


			</div>
			<div id="container_ft">
				<p>&copy; 2007-2009 Fabio Zendhi Nagao (nagaozen) - http://zend.lojcomm.com.br/</p>
				<ul>
					<li><a href="http://validator.w3.org/check?uri=referer"><img src="/common/images/antipixel_xhtml11.gif" alt="Valid XHTML" /></a></li>
					<li><a href="http://jigsaw.w3.org/css-validator/validator?uri=http://zend.lojcomm.com.br/imask/css/this.css"><img src="/common/images/antipixel_css.gif" alt="Valid CSS" /></a></li>
				</ul>
			</div>
		</div>

		<script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
		<script type="text/javascript">
		_uacct = "UA-1957666-1";
		urchinTracker();
		</script>
	</body>
</html>