-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 10, 2017 at 10:11 PM
-- Server version: 5.5.31
-- PHP Version: 5.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `seopanel390`
--

-- --------------------------------------------------------

--
-- Table structure for table `sb_engines`
--

CREATE TABLE IF NOT EXISTS `sb_engines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `engine_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `engine_submit_link` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `engine_register_link` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `engine_login_link` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `rank` int(11) NOT NULL DEFAULT '50',
  `iframe` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `engine_name` (`engine_name`),
  UNIQUE KEY `engine_submit_link` (`engine_submit_link`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=183 ;

--
-- Dumping data for table `sb_engines`
--

INSERT INTO `sb_engines` (`id`, `engine_name`, `engine_submit_link`, `engine_register_link`, `engine_login_link`, `rank`, `iframe`, `status`) VALUES
(1, 'Myfavlink', 'http://www.easysocialbookmarking.com/myfavlink/submit.php?url=[[url]]&title=[[title]]&bodytext=[[description]]&tags=[[tags]]', 'http://www.easysocialbookmarking.com/myfavlink/register.php', 'http://www.easysocialbookmarking.com/myfavlink/login.php', 50, 0, 0),
(2, 'Myspace.com', 'http://www.myspace.com/Modules/PostTo/Pages/?c=[[url]]&t=[[title]]', 'http://myspace.com/', 'http://www.myspace.com/', 6, 0, 1),
(3, 'Twitter.com', 'http://twitter.com/home?status= [[title]] [[url]]', 'http://twitter.com/signup', 'http://twitter.com/', 2, 0, 1),
(4, 'netvibes.com', 'http://www.netvibes.com/share?url=[[url]]&title=[[title]]&src=', 'http://www.netvibes.com/#signup', 'http://www.netvibes.com/signin', 50, 0, 1),
(5, 'wordpress-blog', 'http://www.hasanrang.com/wp-admin/press-this.php?u=[[url]]&t=[[title]]&s=&v=2', 'https://en.wordpress.com/signup/', 'https://en.wordpress.com/wp-login.php', 50, 0, 0),
(6, 'live.com', 'http://profile.live.com/badge?url=[[url]]&title=[[title]]', 'https://signup.live.com/signup.aspx', 'https://login.live.com/login.srf', 14, 0, 1),
(7, 'linkedin.com', 'http://www.linkedin.com/shareArticle?summary=[[description]]&url=[[url]]&source=&title=[[title]]&mini=true', 'http://www.linkedin.com', 'http://www.linkedin.com', 2, 0, 1),
(9, 'Digg.com', 'http://digg.com/submit?phase=2&url=[[url]]&title=[[title]]&bodytext=[[description]]&topic=[[tags]]', 'http://digg.com/register', 'http://digg.com/login', 3, 0, 1),
(10, 'Google.com/Bookmarks', 'http://www.google.com/bookmarks/mark?op=add&bkmk=[[url]]&title=[[title]]&labels=[[tags]],&annotation=[[description]]&cd=bm&btnA=Add', 'http://google.com/', 'https://www.google.com/accounts/', 7, 0, 1),
(11, 'Technorati.com', 'http://technorati.com/faves/?add=[[url]]', 'http://technorati.com/', 'http://technorati.com/signup/', 25, 0, 0),
(12, 'Mister-wong.com', 'http://www.mister-wong.com/index.php?action=addurl&bm_url=[[url]]&bm_description=[[title]]&bm_tags=[[tags]],&bm_notice=[[description]]', 'http://mister-wong.com/', 'http://www.mister-wong.com/?action=login', 50, 0, 0),
(14, 'Blogger.com', 'http://www.blogger.com/blog-this.g?u=[[url]]&n=[[title]]&t=[[description]]', 'http://blogger.com/', 'https://www.blogger.com/start', 7, 0, 1),
(15, 'Tumblr.com', 'http://www.tumblr.com/new/link?post[one]=[[title]]&post[two]=[[url]]&post[three]=[[description]]', 'http://tumblr.com/', 'http://www.tumblr.com/login', 45, 0, 1),
(16, 'connotea.org', 'http://www.connotea.org/add?uri=[[url]]&title=[[title]]&description=[[description]]', 'http://www.connotea.org/register', 'http://www.connotea.org/', 50, 0, 0),
(17, 'typepad.com', 'http://www.typepad.com/services/quickpost/post?v=2&qp_show=[[url]]ac&qp_title=[[title]]&qp_href=[[url]]&qp_text=[[description]]', 'https://www.typepad.com/secure/services/reg', 'https://www.typepad.com/', 50, 0, 1),
(18, 'citeulike.org', 'http://www.citeulike.org/post_unknown.adp?url=[[url]]&title=[[title]]&tags=[[tags]]&meta=', 'http://www.citeulike.org/register', 'http://www.citeulike.org/login', 50, 0, 1),
(19, 'xing.com', 'https://www.xing.com/app/user?op=share&url=[[url]];title=[[title]];provider=', 'http://www.xing.com/app/signup', 'http://www.xing.com/app/user', 50, 0, 1),
(20, 'identi.ca', 'http://identi.ca/notice/new?status_textarea=[[title]] [[url]]', 'https://identi.ca/main/register', 'https://identi.ca/main/login', 50, 0, 0),
(21, 'friendfeed.com', 'http://friendfeed.com/?url=[[url]]&title=[[title]]', 'https://friendfeed.com/account/create', 'https://friendfeed.com/account/login', 10, 0, 0),
(22, 'netlog.com', 'http://netlog.com/go/manage/links/view=save&origin=external&url=[[url]]&title=[[title]]&description=[[description]]', 'http://netlog.com/go/register', 'http://netlog.com/go/login/', 50, 0, 1),
(24, 'Bibsonomy.org', 'http://www.bibsonomy.org/ShowBookmarkEntry?c=b&jump=yes&url=[[url]]&description=[[title]]&extended=[[description]]&tags=[[tags]]', 'http://bibsonomy.org/', 'http://www.bibsonomy.org/login', 50, 0, 1),
(25, 'Blinklist.com', 'http://www.blinklist.com/?Action=Blink/addblink.php&Description=[[description]]&Url=[[url]]&Title=[[title]]&Tag=[[tags]],', 'http://blinklist.com/', 'http://www.blinklist.com/profile/signin.php', 50, 0, 0),
(26, 'Diigo.com', 'http://www.diigo.com/post?b_mode=0&c_mode=0&url=[[url]]&title=[[title]]&comments=[[description]]&tag=[[tags]],', 'http://diigo.com/', 'http://www.diigo.com/sign-in', 50, 0, 1),
(27, 'Multiply.com', 'http://multiply.com/gus/journal/compose/addthis?body=[[description]]&url=[[url]]&subject=[[title]]', 'http://multiply.com/', 'http://multiply.com/', 50, 0, 0),
(29, 'Squidoo.com', 'http://www.squidoo.com/lensmaster/bookmark?[[url]]', 'http://squidoo.com/', 'http://www.squidoo.com/member/login', 50, 0, 0),
(30, 'Jumptags.com', 'http://www.jumptags.com/add/?url=[[url]]&title=[[title]]&new_tag=[[tags]],&common_keywords=[[tags]],&common_description=[[description]]', 'http://jumptags.com/', 'http://www.jumptags.com/my/', 50, 0, 0),
(31, 'del.icio.us', 'http://www.del.icio.us/save?url=[[url]]&title=[[title]]&notes=[[description]]&tags=[[tags]]&noui=no&time=&share=yes&recipients=', 'https://secure.delicious.com/register', 'https://secure.delicious.com/login', 4, 0, 0),
(32, 'bebo.com', 'http://www.bebo.com/c/share?Url=[[url]]&Title=[[title]]&TUUID=&MID=', 'http://www.bebo.com/c/invite/join', 'http://www.bebo.com/SignIn.jsp', 100, 0, 0),
(34, 'posterous.com', 'http://posterous.com/share?linkto=[[url]]&selection=&title=[[title]]', 'https://posterous.com/', 'https://posterous.com/login', 50, 0, 0),
(35, 'bookmarks.yahoo.com', 'http://bookmarks.yahoo.com/toolbar/savebm?u=[[url]]&t=[[title]]&ref=', 'https://edit.yahoo.com/registration', 'https://login.yahoo.com/', 9, 0, 0),
(36, 'orkut.com', 'http://promote.orkut.com/preview?nt=orkut.com&tt=[[title]]&du=[[url]]', 'http://www.orkut.com/PreSignup', 'https://www.google.com/accounts/ServiceLogin?service=orkut', 12, 0, 0),
(37, 'instapaper.com', 'http://www.instapaper.com/edit?url=&title=&summary=', 'http://www.instapaper.com/user/register', 'http://www.instapaper.com/user/login', 50, 0, 1),
(38, 'viadeo.com', 'http://www.viadeo.com/shareit/share/index.jsp?url=[[url]]&title=[[title]]', 'http://join.viadeo.com/step/registration', 'http://www.viadeo.com/en/connexion/', 50, 0, 1),
(39, 'yigg.de', 'http://yigg.de/neu?exturl=[[url]]&exttitle=[[title]]', 'http://yigg.de/profil/register', 'http://yigg.de/', 50, 0, 1),
(40, 'Indianpad.com', 'http://www.indianpad.com/submit.php?url=[[url]]', 'http://indianpad.com/', 'http://www.indianpad.com/login.php?return=/', 50, 0, 0),
(42, 'Folkd.com', 'http://www.folkd.com/?page=submit&check=&addurl=[[url]]', 'http://folkd.com/', 'http://www.folkd.com/page/login.html', 50, 0, 1),
(43, 'tipd.com', 'http://tipd.com/submit.php?url=[[url]]', 'http://tipd.com/register', 'http://tipd.com/login', 50, 0, 0),
(44, 'pusha.se', 'http://www.pusha.se/posta?url=[[url]]', 'http://www.pusha.se/blimedlem', 'http://www.pusha.se/loggain', 50, 0, 1),
(45, 'surphace.com', 'http://www.surphace.com/search?q=sphereit:[[url]]', 'http://www.surphace.com/s4/auth/signup', 'http://www.surphace.com/s4/auth/login', 50, 0, 0),
(46, 'faves.com', 'http://faves.com/Authoring.aspx?u=[[url]]&t=[[title]]&noteText=[[description]]', 'http://faves.com/signIn', 'https://secure.faves.com/signIn', 50, 0, 1),
(47, 'netvouz.com', 'http://www.netvouz.com/action/submitBookmark?url=[[url]]&title=[[title]]&popup=no&description=[[description]]', 'http://www.netvouz.com/action/signUp', 'http://www.netvouz.com/action/logon', 50, 0, 1),
(48, 'oneview.de', 'http://www.oneview.de/add/?title=[[title]]&URL=[[url]]', 'http://www.oneview.de/registrierung/', 'http://www.oneview.de/login/', 50, 0, 0),
(50, 'sphinn.com', 'http://sphinn.com/index.php?c=post&m=submit&url=[[url]]', 'http://sphinn.com/register/', 'http://sphinn.com/login/', 50, 0, 1),
(51, 'hellotxt.com', 'http://hellotxt.com/?status=[[title]] [[url]]', 'http://hellotxt.com/', 'http://hellotxt.com/', 50, 0, 0),
(52, 'plaxo.com', 'http://www.plaxo.com/?share_link=[[url]]', 'https://www.plaxo.com/join', 'http://www.plaxo.com/auth', 50, 0, 1),
(53, 'diglog.com', 'http://www.diglog.com/submit?act=like&returnurl=true&title=[[title]]&url=[[url]]', 'http://www.diglog.com/register.aspx', 'http://www.diglog.com/signin.aspx', 50, 0, 0),
(54, 'allvoices.com', 'http://www.allvoices.com/post_event?url=[[url]]&title=[[title]]', 'http://www.allvoices.com/', 'http://www.allvoices.com/', 50, 0, 0),
(55, 'readitlaterlist.com', 'https://readitlaterlist.com/edit?url=[[url]]&title=[[title]]', 'https://readitlaterlist.com/signup', 'https://readitlaterlist.com/l', 50, 0, 1),
(56, 'printfriendly.com', 'http://www.printfriendly.com/print/v2?url=[[url]]', 'http://www.printfriendly.com/', 'http://www.printfriendly.com/', 50, 0, 1),
(57, 'mypage.rediff.com', 'http://share.rediff.com/bookmark/addbookmark?bookmarkurl=[[url]]&title=[[title]]', 'http://mypage.rediff.com/', 'http://mypage.rediff.com/', 12, 1, 1),
(58, 'Furl.net', 'http://www.furl.net/savedialog.jsp?p=1&t=&u=[[url]]&r=&v=1&c=&topics=[[title]]&description=[[description]]&keywords=[[tags]],', 'http://furl.net/', 'http://www.furl.net/members/login', 21, 0, 0),
(60, 'Blogmarks.net', 'http://blogmarks.net/my/marks,new?url=[[url]]&title=[[title]]&content=[[description]]&public-tags=[[tags]],&private-tags=[[tags]],&via=', 'http://blogmarks.net/', 'http://blogmarks.net/', 50, 0, 1),
(61, 'Thoof.com', 'http://thoof.com/submit/?summary=[[description]]&title=[[title]]&link=[[url]]', 'http://thoof.com/', 'http://thoof.com/home.7', 50, 0, 0),
(62, 'Corank.com', 'http://www.corank.com/submit?url=[[url]]&title=[[title]]&source=w', 'http://corank.com/', 'http://www.corank.com/', 50, 0, 0),
(63, 'Swik.net', 'http://stories.swik.net/?bookmarklet=v1&submitUrl&url=[[url]]&title=[[title]]&description=[[description]]', 'http://swik.net/', 'http://swik.net/?register', 50, 0, 0),
(64, 'Dropjack.com', 'http://www.dropjack.com/submit.php?url=[[url]]&title=[[title]]', 'http://dropjack.com/', 'http://www.dropjack.com/login.php', 50, 0, 1),
(66, 'Bookmarktracker.com', 'http://www.bookmarktracker.com/frame_add.cfm?url=[[url]]&title=[[title]]', 'http://bookmarktracker.com/', 'http://www.bookmarktracker.com/bt/5643920.99479915/login', 50, 0, 0),
(67, 'Searchles.com', 'http://www.searchles.com/links/add_link?url=[[url]]&title=[[title]]&desc=[[description]]&tags=[[tags]],', 'http://searchles.com/', 'http://www.searchles.com/login', 50, 0, 0),
(68, 'dailyme.com', 'http://dailyme.com/bookmarklet?u=[[url]]', 'http://dailyme.com/accounts/create', 'http://dailyme.com/', 50, 0, 0),
(70, 'protopage.com', 'http://www.protopage.com/add-button-site?url=&label=&type=page', 'http://www.protopage.com/', 'http://www.protopage.com/', 50, 0, 1),
(71, 'wists.com', 'http://wists.com/u.php?r=[[url]]', 'http://wists.com/?action=signup_form&amp;add', 'http://wists.com/?action=login_form', 50, 0, 0),
(72, 'sitejot.com', 'http://www.sitejot.com/addform.php?iSiteAdd=[[url]]&iSiteDes=[[title]]', 'http://www.sitejot.com/signup.php', 'http://www.sitejot.com/', 50, 0, 1),
(73, 'buddymarks.com', 'http://buddymarks.com/add_bookmark.php?bookmark_url=[[url]]&bookmark_title=[[title]]&bookmark_desc=[[description]]', 'http://buddymarks.com/signup.php', 'http://www.buddymarks.com/login.php', 50, 0, 1),
(74, 'linkagogo.com', 'http://www.linkagogo.com/go/AddNoPopup?url=[[url]]&title=[[title]]', 'http://www.linkagogo.com/go/UserInfo', 'https://www.linkagogo.com/go/Authenticate', 50, 0, 0),
(75, 'unalog.com', 'http://unalog.com/my/stack/link?url=[[url]]&title=[[title]]', 'http://unalog.com/register/', 'http://unalog.com/login/', 50, 0, 0),
(76, 'gabbr.com', 'http://www.gabbr.com/submit/?bookurl=[[url]]', 'http://www.gabbr.com/register/', 'http://www.gabbr.com/login/', 50, 0, 0),
(77, 'tagza.com', 'http://tagza.com/submit.php?url=', 'http://tagza.com/register.php', 'http://tagza.com/login', 50, 0, 0),
(78, 'yoolink.fr', 'http://www.yoolink.fr/addorshare?f=aa&kind=0&title=[[title]]&url_value=[[url]]', 'http://auth.yoolink.to/register/new', 'http://auth.yoolink.to/authenticate/login', 50, 0, 1),
(79, 'Connectedy.com', 'http://www.connectedy.com/add-link.php?remote=1&url=[[url]]&title=[[title]]', 'http://connectedy.com/', 'http://www.connectedy.com/index.php', 50, 0, 1),
(81, 'imera.com.br', 'http://www.imera.com.br/post_d.html?linkName=[[title]]&linkUrl=[[url]]', 'http://www.imera.com.br/user_add_d.html', 'http://www.imera.com.br/signin_d.html', 50, 0, 0),
(82, 'Mylinkvault.com', 'http://www.mylinkvault.com/link-quick.php?u=[[url]]&n=[[title]]', 'http://mylinkvault.com/', 'http://www.mylinkvault.com/users/', 50, 0, 0),
(83, 'xerpi.com', 'http://www.xerpi.com/block/add_link_from_extension?url=[[url]]&title=[[title]]', 'http://www.xerpi.com/account/login', 'http://www.xerpi.com/account/login', 50, 0, 1),
(84, 'linkatopia.com', 'http://linkatopia.com/login?uri=[[url]];title=[[title]]', 'http://linkatopia.com/signup', 'http://linkatopia.com/login', 50, 0, 1),
(85, 'yample.com', 'http://www.yample.com/submit.php?url=[[url]]', 'http://www.yample.com/', 'http://www.yample.com/', 50, 0, 0),
(86, 'Business-planet.net', 'http://www.business-planet.net/submit?url=[[url]]&title=[[title]]', 'http://business-planet.net/', 'http://www.business-planet.net/login/', 50, 0, 0),
(88, 'fitness.strands.com', 'https://fitness.strands.com/tools/share/webpage?title=[[title]]&url=[[url]]', 'http://fitness.strands.com/#', 'http://fitness.strands.com/#', 50, 0, 0),
(89, 'Megite.com/discover', 'http://www.megite.com/my/bookmarks.php/?action=add&address=[[url]]&title=[[title]]&description=[[description]]&tags=[[tags]],', 'http://megite.com/discover/', 'http://www.megite.com//my/login.php', 50, 0, 0),
(90, 'khabbr.com', 'http://www.khabbr.com/submit.php?out=yes&url=[[url]]', 'http://www.khabbr.com/', 'http://www.khabbr.com/', 50, 0, 0),
(91, 'spurl.net', 'http://www.spurl.net/spurl.php?title=[[title]]&url=[[url]]', 'http://www.spurl.net/', 'http://www.spurl.net/', 50, 0, 0),
(92, 'zootool.com', 'http://zootool.com/post/?url=[[url]]&title=[[title]]', 'http://zootool.com/', 'http://zootool.com/', 50, 0, 0),
(93, 'zing.vn', 'http://link.apps.zing.vn/share?url=[[url]]&title=[[title]]', 'http://www.zing.vn/', 'http://www.zing.vn/', 50, 0, 1),
(94, 'ziczac.it', 'http://ziczac.it/a/segnala/?gurl=[[url]]&gtit=', 'http://ziczac.it/a/login/?PostBackAction=ApplyForm', 'http://ziczac.it/a/login/', 50, 0, 1),
(95, 'zakladok.net', 'http://zakladok.net/link/?u=[[url]]&t=[[title]]', 'http://zakladok.net/register.php', 'http://www.zakladok.net/', 50, 0, 1),
(96, 'yuuby.com', 'http://www.yuuby.com/sharer.php?url=[[url]]&title=[[title]]', 'http://yuuby.com', 'http://www.yuuby.com/register.php', 50, 0, 1),
(97, 'youbookmarks.com', 'http://youbookmarks.com/api/quick_add.php?version=1&url=[[url]]&title=[[title]]', 'http://youbookmarks.com/sign_up', 'http://youbookmarks.com/', 50, 0, 1),
(98, 'yorumcuyum.com', 'http://www.yorumcuyum.com/?baslik=[[title]]&link=[[url]]', '', '', 50, 0, 1),
(99, 'yemle.com', 'http://www.yemle.com/submit?url=[[url]]&title=[[title]]', 'http://www.yemle.com/signup', 'http://www.yemle.com/login.php', 50, 0, 0),
(100, 'yardbarker.com', 'http://www.yardbarker.com/forum/create_discussion?headline=[[title]]&reference_url=[[url]]', 'http://www.yardbarker.com/account/register', 'http://www.yardbarker.com/account/signin', 50, 0, 1),
(102, 'mail.yahoo.com', 'http://compose.mail.yahoo.com/?To=&Subject=[[title]]&body=[[url]] [[description]]', 'https://edit.yahoo.com/registration', 'https://login.yahoo.com/', 20, 0, 1),
(103, 'worio.com', 'http://www.worio.com/search/preview/?action=save&wref=addthis&u=[[url]]&t=[[title]]', 'http://www.worio.com/', 'http://www.worio.com/login/u/', 50, 0, 0),
(104, 'wirefan.com', 'http://www.wirefan.com/grpost.php?d=&u=[[url]]&h=[[title]]', 'http://www.wirefan.com/public', 'http://www.wirefan.com/public', 50, 0, 1),
(105, 'windycitizen.com', 'http://www.windycitizen.com/submit?url=[[url]]&title=[[title]]&body=[[description]]', 'http://www.windycitizen.com/', 'http://www.windycitizen.com/', 50, 0, 0),
(106, 'vkontakte.ru', 'http://vkontakte.ru/share.php?url=[[url]]&title=[[title]]', 'http://vkontakte.ru/reg0', 'http://vkontakte.ru/login.php', 50, 0, 1),
(107, 'voxopolis.com', 'http://www.voxopolis.com/oexchange/?url=[[url]]&title=[[title]]', 'http://www.voxopolis.com/pick_plan/', 'http://www.voxopolis.com/user', 50, 0, 0),
(108, 'vybrali.sme.sk', 'http://vybrali.sme.sk/sub.php?url=[[url]]', 'http://vybrali.sme.sk/pridaj', '', 50, 0, 1),
(109, 'vkrugudruzei.ru', 'http://vkrugudruzei.ru/x/button?url=[[url]]&title=[[title]]', '', '', 50, 0, 1),
(110, 'virb.com', 'http://virb.com/share?external&v=2&url=[[url]]&title=[[title]]', 'http://virb.com/signup', 'http://virb.com/#login', 50, 0, 1),
(111, 'upnews.it', 'http://www.upnews.it/submit.php?url=[[url]]&title=[[title]]', 'http://www.upnews.it/register/', 'http://www.upnews.it/login.php', 50, 0, 0),
(112, 'twitthis.com', 'http://twitthis.com/twit?url=[[url]]&title=[[title]]', 'http://twitthis.com/', 'http://twitthis.com/', 50, 0, 0),
(113, 'tvinx.com', 'http://www.tvinx.com/question.php?url=[[url]]&title=[[title]]', 'http://www.tvinx.com/register.en', 'http://www.tvinx.com/', 50, 0, 0),
(114, 'tusulhaber.com', 'http://www.tusulhaber.com/submit.php?url=[[url]]&title=[[title]]', 'http://www.tusulhaber.com/register.php', 'http://www.tusulhaber.com/login.php', 50, 0, 1),
(115, 'hotklix.com', 'http://www.hotklix.com/addlink.html?profilelink=[[url]];[[title]]', 'http://www.hotklix.com/', 'http://www.hotklix.com/', 50, 0, 0),
(116, 'blurpalicious.com', 'http://www.blurpalicious.com/submit/?url=[[url]]&title=[[title]]&tags=[[tags]]&bodytext=[[description]]', 'http://www.blurpalicious.com/register', 'http://www.blurpalicious.com/', 50, 0, 1),
(117, 'designfloat.com', 'http://www.designfloat.com/submit?url=[[url]]&tags=&bodytext', 'http://www.designfloat.com/register/', 'http://www.designfloat.com/login/', 50, 0, 1),
(118, 'tuenti.com', 'http://www.tuenti.com/share?url=[[url]]', 'http://www.tuenti.com/', 'http://www.tuenti.com/', 50, 0, 1),
(119, 'ekle.topsiteler.net', 'http://ekle.topsiteler.net/submit.php?url=[[url]]&title=[[title]]', 'http://ekle.topsiteler.net/register/', 'http://ekle.topsiteler.net/login.php', 50, 0, 1),
(120, '100zakladok.ru', 'http://www.100zakladok.ru/save/?bmurl=[[url]]&bmtitle=[[title]]', 'http://www.100zakladok.ru/register/', 'http://www.100zakladok.ru/login/', 50, 0, 1),
(121, 'qaafe.com', 'http://qaafe.com/submit?url=[[url]]&title=[[title]]&tags=[[tags]]&bodytext=[[description]]', 'http://qaafe.com/register', 'http://qaafe.com/login.php', 50, 0, 0),
(122, 'kwoff.com', 'http://kwoff.com/submit.php?url=[[url]]&tags=[[tags]]&bodytext=[[description]]', 'http://kwoff.com/register.php', 'http://kwoff.com/login.php', 50, 0, 0),
(123, 'humsurfer.com', 'http://www.humsurfer.com/news/submit?link=[[url]]&submit=Continue &news[title]=[[title]]&news[body]=', 'http://www.humsurfer.com/', 'http://www.humsurfer.com/', 50, 0, 0),
(124, 'care2.com', 'http://www.care2.com/news/compose?sharehint=news&share[share_type]news&bookmarklet=Y&share[title]=[[title]]&share[link_url]=[[url]]&share[content]=[[description]]', 'http://www.care2.com/passport/signup.html', 'http://www.care2.com/passport/login.html', 50, 0, 1),
(125, 'cirip.ro', 'http://www.cirip.ro/post/?url=[[url]]&bookmark=[[title]]', 'http://www.cirip.ro/', 'http://www.cirip.ro/', 50, 0, 0),
(126, 'classicalplace.com', 'http://www.classicalplace.com/?u=[[url]]&t=[[title]]&c=', 'http://www.classicalplace.com/', 'http://www.classicalplace.com/', 50, 0, 0),
(127, 'colivia.de', 'http://www.colivia.de/submit.php?url=[[url]]', 'http://www.colivia.de/register.php', 'http://www.colivia.de/login.php', 50, 0, 0),
(128, 'cndig.org', 'http://www.cndig.org/submit/?url=[[url]]', 'http://www.cndig.org/register/', 'http://www.cndig.org/login.php', 50, 0, 0),
(129, 'technerd.com', 'http://technerd.com/share.php?url=[[url]]&title=[[title]]', 'https://www.technerd.com/register.php', 'http://www.technerd.com/', 50, 0, 1),
(130, 'cootopia.com', 'http://www.cootopia.com/create-news?url=[[url]]&title=[[title]]', 'http://www.cootopia.com/', 'http://www.cootopia.com/', 50, 0, 0),
(131, 'cosmiq.de', 'http://www.cosmiq.de/lili/my/add?url=[[url]]&title=[[title]]&tags=[[tags]]', 'http://www.cosmiq.de/home/register/', 'http://www.cosmiq.de/', 50, 0, 1),
(132, 'mypip.com', 'http://www.mypip.com/addtomypip.html?name=[[title]]&addr=[[url]]', 'http://www.mypip.com/', 'http://www.mypip.com/', 50, 0, 0),
(133, 'feedmarker.com', 'http://www.feedmarker.com/admin.php?do=bookmarklet_mark&url=[[url]]&title=[[title]]', 'http://www.feedmarker.com/register', 'http://www.feedmarker.com/user/login', 50, 0, 1),
(134, 'zurpy.com', 'http://tag.zurpy.com/?box=1&url=[[url]]&title=[[title]]', 'http://zurpy.com/#/signup-1', 'http://zurpy.com/', 50, 0, 0),
(136, 'jokke.dk', 'http://jokke.dk/yoghurt/dummy.php?url=[[url]]&title=[[title]]&top=1', '', 'http://yoghurt.jokke.dk/login.php', 50, 0, 0),
(137, 'mybookmarkmanager.com', 'http://www.mybookmarkmanager.com/quickBookmark.view?quickBookmark=true&url=[[url]]&name=[[title]]', 'http://www.mybookmarkmanager.com/', 'http://www.mybookmarkmanager.com/', 50, 0, 0),
(139, 'myweb.yahoo.com', 'http://myweb.yahoo.com/myresults/bookmarklet?t=[[title]]&u=[[url]]&ei=UTF', 'https://login.yahoo.com/config/login', 'https://login.yahoo.com/config/login', 10, 0, 0),
(140, 'memeflow.com', 'http://www.memeflow.com/goto/goto.php?cmd=itemfromsc&url=[[url]]', 'http://www.memeflow.com/goto/', 'http://www.memeflow.com/goto/', 50, 0, 1),
(141, 'eggkeg.com', 'http://www.eggkeg.com/bookmarks/new?url=[[url]]&title=[[title]]', 'http://www.eggkeg.com/', 'http://www.eggkeg.com/', 50, 0, 0),
(142, 'markaboo.com', 'http://www.markaboo.com/resources/new?url=[[url]]&title=[[title]]', '', '', 50, 0, 1),
(143, 'earthlink.net', 'http://myfavorites.earthlink.net/my/add_favorite?v=1&url=[[url]]&title=[[title]]', 'http://www.earthlink.net/', 'https://myaccount.earthlink.net/cam/login.jsp', 50, 0, 1),
(144, 'matrix.msu.edu', 'http://www.matrix.msu.edu/~mmatrix/bookmark_editor.php?url=[[url]]&t=[[title]]', '', '', 50, 0, 0),
(145, 'lycos.co.uk', 'http://iq.lycos.co.uk/lili/my/add?url=[[url]]', '', '', 50, 0, 1),
(147, 'linkroll.com', 'http://linkroll.com/insert.php?url=[[url]]&title=[[title]]', 'http://www.linkroll.com/index.php?action=registration', 'http://www.linkroll.com/index.php?action=login', 50, 0, 1),
(148, 'linkarena.com', 'http://www.linkarena.com/linkadd.php?linkName=[[title]]&linkURL=[[url]]', 'http://linkarena.com/register', 'http://linkarena.com/login', 50, 0, 0),
(150, 'eigology.com', 'http://www.eigology.com/dogear/gettext.php?u=[[url]]&t=[[title]]', 'http://www.eigology.com/dogear/index.php', 'http://www.eigology.com/dogear/index.php', 50, 0, 0),
(151, 'sync2it.com', 'http://www.sync2it.com/addbm.php?url=[[url]]&title=[[title]]', 'http://www.sync2it.com/common/login.php#register', 'http://www.sync2it.com/', 50, 0, 0),
(152, 'indiza.com', 'http://indiza.com/edit.php?show&redirect&link=[[url]]&description=[[title]]', 'http://indiza.com/createuser.php?show', 'http://indiza.com/', 50, 0, 1),
(153, 'complore.com', 'http://complore.com/?q=node/add/flexinode-5&url=[[url]]&title=[[title]]', 'http://www.complore.com/user/register', 'http://www.complore.com/user', 50, 0, 0),
(154, 'claimid.com', 'http://claimid.com/post/?url=[[url]]&title=[[title]]', 'http://claimid.com/', 'http://claimid.com/', 50, 0, 0),
(155, 'i89.us', 'http://www.i89.us/bookmark.php?url=[[url]]&title=[[title]]', 'http://www.i89.us/registration.php', 'http://www.i89.us/mypage.php', 50, 0, 0),
(156, 'butterflyproject.nl', 'http://www.butterflyproject.nl/myaccount/add_mybutterly.php?referer=nonpopup&title=[[title]]&url=[[url]]', 'http://www.butterflyproject.nl/users/register.php', 'http://www.butterflyproject.nl/users/signIn.php', 50, 0, 1),
(157, 'gravee.com', 'http://www.gravee.com/account/bookmarkpop?u=[[url]]&t=[[title]]', 'http://www.gravee.com/account/signup', 'http://www.gravee.com/account/login', 50, 0, 0),
(158, 'bmaccess.net', 'http://www.bmaccess.net/webtool/crap.php?title=[[title]]&url=[[url]]', 'http://www.bmaccess.net/index.php?action=register', 'http://www.bmaccess.net/login', 50, 0, 0),
(159, 'upenn.edu', 'http://tags.library.upenn.edu/cgi-bin/annogine/postnote?aid=a;title=[[title]]&url=[[url]]', 'http://tags.library.upenn.edu/', 'http://tags.library.upenn.edu/', 50, 0, 0),
(160, 'nowpublic.com', 'http://view.nowpublic.com/?src=[[url]]&t=[[title]]', 'http://www.nowpublic.com/user/register', 'http://www.nowpublic.com/user/login', 50, 0, 0),
(161, 'bookmarks.ning.com', 'http://bookmarks.ning.com/addItem.php?url=[[url]]&title=[[title]]', 'http://bookmarks.ning.com/main/authorization/signIn', 'http://bookmarks.ning.com/main/authorization/signIn', 50, 0, 0),
(162, 'nextaris.com', 'http://www.nextaris.com/servlet/com.surfwax.Nextaris.Bookmarklets?cmd=addurlrequest&v=1&url=[[url]]&title=[[title]]', 'http://www.nextaris.com/', 'http://www.nextaris.com/index-login.html', 50, 0, 1),
(163, 'scuttle.org', 'http://scuttle.org/bookmarks.php/?action=add&address=[[url]]&title=[[title]]', 'http://sourceforge.net/user/registration/', 'https://sourceforge.net/account/login.php', 50, 0, 0),
(164, 'scoopeo.com', 'http://www.scoopeo.com/scoop/new?newurl=[[url]]&title=[[title]]', 'http://www.scoopeo.com/signup', 'http://www.scoopeo.com/login', 50, 0, 0),
(165, 'haohaoreport.com', 'http://www.haohaoreport.com/submit.php?url=[[url]]&title=[[title]]', 'http://www.haohaoreport.com/user/register', 'http://www.haohaoreport.com/user', 50, 0, 1),
(166, 'book.mark.hu', 'http://book.mark.hu/bookmarks.php/?action=add&address=[[url]]&title=[[title]]', 'http://linkr.hu/register/', 'http://linkr.hu/login.php', 50, 0, 0),
(167, 'gwar.pl', 'http://www.gwar.pl/DodajGwar.html?u=[[url]]', 'http://www.gwar.pl/', 'http://www.gwar.pl/loginForm.html', 50, 0, 0),
(168, 'co.mments.com', 'http://co.mments.com/track?url=[[url]]&title=[[title]]', 'http://co.mments.com/login', 'http://co.mments.com/login', 50, 0, 0),
(169, 'fark.com', 'http://cgi.fark.com/cgi/fark/edit.pl?new_url=[[url]]&new_comment=[[title]]&new_comment=BLOGNAME&linktype=Misc', 'http://www.fark.com/cgi/newuser.pl', 'http://www.fark.com/', 50, 0, 1),
(172, 'givealink.org', 'http://www.givealink.org/cgi-pub/bookmarklet/bookmarkletLogin.cgi?&uri=[[url]]&title=[[title]]', 'http://www.givealink.org/account/new', 'http://www.givealink.org/user_session/new', 50, 0, 1),
(173, 'igooi.com', 'http://www.igooi.com/addnewitem.aspx?self=1&noui=yes&jump=close&url=[[url]]&title=[[title]]', 'http://www.igooi.com/', 'http://www.igooi.com/', 50, 0, 0),
(174, 'isedb.com', 'http://scoop.isedb.com/submit.php?url=[[url]]&title=[[title]]', 'http://isedb.com/', 'http://isedb.com/', 50, 0, 0),
(175, 'wink.com', 'http://www.wink.com/_/tag?url=[[url]]&doctitle=[[title]]', '', '', 50, 0, 1),
(176, 'tellfriends.com', 'http://tellfriends.com/topics/create?url=[[url]]', 'http://tellfriends.com/', 'http://tellfriends.com/', 50, 0, 0),
(177, 'facebook.com', 'http://www.facebook.com/share.php?src=bm&u=[[url]]&t=[[title]]&sharer_popup_message=[[description]]&v=3', 'http://facebook.com/', 'http://www.facebook.com/login.php', 1, 0, 1),
(178, 'reddit.com', 'http://reddit.com/submit?url=[[url]]&title=[[title]]', 'http://reddit.com/login', 'http://reddit.com/login', 8, 0, 1),
(179, 'stumbleupon', 'http://www.stumbleupon.com/submit?url=[[url]]&title=[[title]]&newcomment=[[description]]&tagnames=%20[[tags]]', 'http://www.stumbleupon.com/sign_up.php', 'http://www.stumbleupon.com/login.php', 5, 0, 1),
(180, 'tagged.com', 'http://www.tagged.com/submit?url=[[url]]&title=[[title]]', 'http://www.tagged.com/register.html?display=login', 'http://www.tagged.com/register.html?display=login', 14, 0, 1),
(181, 'google+', 'https://plus.google.com/share?url=[[url]]', 'https://accounts.google.com/SignUp', 'https://accounts.google.com/ServiceLogin', 2, 0, 1),
(182, 'Pinterest', 'http://pinterest.com/pin/create/button/?url=[[url]]&description=[[title]] - [[description]] ', 'https://www.pinterest.com/', 'https://www.pinterest.com/login/', 3, 0, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
