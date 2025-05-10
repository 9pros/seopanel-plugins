--
-- Local search engine germany(de)
--

INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.de', 'http://www.google.de/search?hl=de&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0),
('yahoo.de', 'http://de.search.yahoo.com/search?p=[--keyword--]&n=[--num--]&b=[--start--]&vl=lang_[--lang--]&fl=1&v=1&vc=[--country--]', '', 100, 1, 100, 100, '<li.*?<h3><a.*?\\*\\*(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<div.*?>(.*?)<\\/div>.*?<\\/span>', NULL, NULL, 1, 2, 3, NULL, 0),
('bing.de', 'http://www.bing.com/search?q=[--keyword--]&scope=web&first=[--start--]&setmkt=[--lang--]-de', 'SRCHHPGUSR=NEWWND=0&NRSLT=50&SRCHLANG=[--lang--]', 50, 1, 50, 100, '<li.*?<h3><a.*?href="(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<p.*?>(.*?)<\\/p>', NULL, NULL, 1, 2, 3, NULL, 0);

--
-- Local search engine france(fr)
--

INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.fr', 'http://www.google.fr/search?hl=fr&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0),
('yahoo.fr', 'http://fr.search.yahoo.com/search?p=[--keyword--]&n=[--num--]&b=[--start--]&vl=lang_[--lang--]&fl=1&v=1&vc=[--country--]', '', 100, 1, 100, 100, '<li.*?<h3><a.*?\\*\\*(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<div.*?>(.*?)<\\/div>.*?<\\/span>', NULL, NULL, 1, 2, 3, NULL, 0),
('bing.fr', 'http://www.bing.com/search?q=[--keyword--]&scope=web&first=[--start--]&setmkt=[--lang--]-fr', 'SRCHHPGUSR=NEWWND=0&NRSLT=50&SRCHLANG=[--lang--]', 50, 1, 50, 100, '<li.*?<h3><a.*?href="(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<p.*?>(.*?)<\\/p>', NULL, NULL, 1, 2, 3, NULL, 0);

--
-- Local search engine spain(es)
--

INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.es', 'http://www.google.es/search?hl=es&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0),
('yahoo.es', 'http://es.search.yahoo.com/search?p=[--keyword--]&n=[--num--]&b=[--start--]&vl=lang_[--lang--]&fl=1&v=1&vc=[--country--]', '', 100, 1, 100, 100, '<li.*?<h3><a.*?\\*\\*(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<div.*?>(.*?)<\\/div>.*?<\\/span>', NULL, NULL, 1, 2, 3, NULL, 0),
('bing.es', 'http://www.bing.com/search?q=[--keyword--]&scope=web&first=[--start--]&setmkt=[--lang--]-es', 'SRCHHPGUSR=NEWWND=0&NRSLT=50&SRCHLANG=[--lang--]', 50, 1, 50, 100, '<li.*?<h3><a.*?href="(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<p.*?>(.*?)<\\/p>', NULL, NULL, 1, 2, 3, NULL, 0);

--
-- Local search engine italy(it)
--

INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.it', 'http://www.google.it/search?hl=it&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0),
('yahoo.it', 'http://it.search.yahoo.com/search?p=[--keyword--]&n=[--num--]&b=[--start--]&vl=lang_[--lang--]&fl=1&v=1&vc=[--country--]', '', 100, 1, 100, 100, '<li.*?<h3><a.*?\\*\\*(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<div.*?>(.*?)<\\/div>.*?<\\/span>', NULL, NULL, 1, 2, 3, NULL, 0),
('bing.it', 'http://www.bing.com/search?q=[--keyword--]&scope=web&first=[--start--]&setmkt=[--lang--]-it', 'SRCHHPGUSR=NEWWND=0&NRSLT=50&SRCHLANG=[--lang--]', 50, 1, 50, 100, '<li.*?<h3><a.*?href="(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<p.*?>(.*?)<\\/p>', NULL, NULL, 1, 2, 3, NULL, 0);

--
-- Local search engine Australia(com.au)
--

INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.com.au', 'http://www.google.com.au/search?hl=[--lang--]&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0),
('yahoo.au', 'http://au.search.yahoo.com/search?p=[--keyword--]&n=[--num--]&b=[--start--]&vl=lang_[--lang--]&fl=1&v=1&vc=[--country--]', '', 100, 1, 100, 100, '<li.*?<h3><a.*?\\*\\*(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<div.*?>(.*?)<\\/div>.*?<\\/span>', NULL, NULL, 1, 2, 3, NULL, 0),
('bing.au', 'http://www.bing.com/search?q=[--keyword--]&scope=web&first=[--start--]&setmkt=[--lang--]-au', 'SRCHHPGUSR=NEWWND=0&NRSLT=50&SRCHLANG=[--lang--]', 50, 1, 50, 100, '<li.*?<h3><a.*?href="(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<p.*?>(.*?)<\\/p>', NULL, NULL, 1, 2, 3, NULL, 0);

--
-- Local search engine belgium(be)
--

INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.be', 'http://www.google.be/search?hl=nl&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0),
('bing.be', 'http://www.bing.com/search?q=[--keyword--]&scope=web&first=[--start--]&setmkt=nl-be', 'SRCHHPGUSR=NEWWND=0&NRSLT=50&SRCHLANG=[--lang--]', 50, 1, 50, 100, '<li.*?<h3><a.*?href="(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<p.*?>(.*?)<\\/p>', NULL, NULL, 1, 2, 3, NULL, 0);

--
-- Local search engine canada(ca)
--

INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.ca', 'http://www.google.ca/search?hl=en&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0),
('yahoo.ca', 'http://ca.search.yahoo.com/search?p=[--keyword--]&n=[--num--]&b=[--start--]&vl=lang_[--lang--]&fl=1&v=1&vc=[--country--]', '', 100, 1, 100, 100, '<li.*?<h3><a.*?\\*\\*(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<div.*?>(.*?)<\\/div>.*?<\\/span>', NULL, NULL, 1, 2, 3, NULL, 0),
('bing.ca', 'http://www.bing.com/search?q=[--keyword--]&scope=web&first=[--start--]&setmkt=[--lang--]-ca', 'SRCHHPGUSR=NEWWND=0&NRSLT=50&SRCHLANG=[--lang--]', 50, 1, 50, 100, '<li.*?<h3><a.*?href="(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<p.*?>(.*?)<\\/p>', NULL, NULL, 1, 2, 3, NULL, 0);

--
-- Local search engine switzerland(ch)
--

INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.ch', 'http://www.google.ch/search?hl=de&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0),
('yahoo.ch', 'http://ch.search.yahoo.com/search?p=[--keyword--]&n=[--num--]&b=[--start--]&vl=lang_[--lang--]&fl=1&v=1&vc=[--country--]', '', 100, 1, 100, 100, '<li.*?<h3><a.*?\\*\\*(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<div.*?>(.*?)<\\/div>.*?<\\/span>', NULL, NULL, 1, 2, 3, NULL, 0),
('bing.ch', 'http://www.bing.com/search?q=[--keyword--]&scope=web&first=[--start--]&setmkt=de-ch', 'SRCHHPGUSR=NEWWND=0&NRSLT=50&SRCHLANG=[--lang--]', 50, 1, 50, 100, '<li.*?<h3><a.*?href="(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<p.*?>(.*?)<\\/p>', NULL, NULL, 1, 2, 3, NULL, 0);

--
-- Local search engine China(cn)
--

INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('yahoo.cn', 'http://cn.search.yahoo.com/search?p=[--keyword--]&n=[--num--]&b=[--start--]&vl=lang_[--lang--]&fl=1&v=1&vc=[--country--]', '', 100, 1, 100, 100, '<li.*?<h3><a.*?\\*\\*(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<div.*?>(.*?)<\\/div>.*?<\\/span>', NULL, NULL, 1, 2, 3, NULL, 0),
('bing.cn', 'http://www.bing.com/search?q=[--keyword--]&scope=web&first=[--start--]&setmkt=[--lang--]-cn', 'SRCHHPGUSR=NEWWND=0&NRSLT=50&SRCHLANG=[--lang--]', 50, 1, 50, 100, '<li.*?<h3><a.*?href="(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<p.*?>(.*?)<\\/p>', NULL, NULL, 1, 2, 3, NULL, 0);

--
-- Local search engine hongkong(hk)
--

INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.com.hk', 'http://www.google.com.hk/search?hl=zh-TW&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0),
('yahoo.hk', 'http://hk.search.yahoo.com/search?p=[--keyword--]&n=[--num--]&b=[--start--]&vl=lang_[--lang--]&fl=1&v=1&vc=[--country--]', '', 100, 1, 100, 100, '<li.*?<h3><a.*?\\*\\*(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<div.*?>(.*?)<\\/div>.*?<\\/span>', NULL, NULL, 1, 2, 3, NULL, 0),
('bing.hk', 'http://www.bing.com/search?q=[--keyword--]&scope=web&first=[--start--]&setmkt=[--lang--]-hk', 'SRCHHPGUSR=NEWWND=0&NRSLT=50&SRCHLANG=[--lang--]', 50, 1, 50, 100, '<li.*?<h3><a.*?href="(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<p.*?>(.*?)<\\/p>', NULL, NULL, 1, 2, 3, NULL, 0);

--
-- Local search engine denmark(dk)
--

INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.dk', 'http://www.google.dk/search?hl=da&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0),
('yahoo.dk', 'http://dk.search.yahoo.com/search?p=[--keyword--]&n=[--num--]&b=[--start--]&vl=lang_[--lang--]&fl=1&v=1&vc=[--country--]', '', 100, 1, 100, 100, '<li.*?<h3><a.*?\\*\\*(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<div.*?>(.*?)<\\/div>.*?<\\/span>', NULL, NULL, 1, 2, 3, NULL, 0),
('bing.dk', 'http://www.bing.com/search?q=[--keyword--]&scope=web&first=[--start--]&setmkt=[--lang--]-dk', 'SRCHHPGUSR=NEWWND=0&NRSLT=50&SRCHLANG=[--lang--]', 50, 1, 50, 100, '<li.*?<h3><a.*?href="(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<p.*?>(.*?)<\\/p>', NULL, NULL, 1, 2, 3, NULL, 0);

--
-- Local search engine ireland(ie)
--

INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.ie', 'http://www.google.ie/search?hl=[--lang--]&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0),
('yahoo.ie', 'http://uk.search.yahoo.com/search?p=[--keyword--]&n=[--num--]&b=[--start--]&vl=lang_[--lang--]&fl=1&v=1&vc=[--country--]', '', 100, 1, 100, 100, '<li.*?<h3><a.*?\\*\\*(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<div.*?>(.*?)<\\/div>.*?<\\/span>', NULL, NULL, 1, 2, 3, NULL, 0),
('bing.ie', 'http://www.bing.com/search?q=[--keyword--]&scope=web&first=[--start--]&setmkt=[--lang--]-ie', 'SRCHHPGUSR=NEWWND=0&NRSLT=50&SRCHLANG=[--lang--]', 50, 1, 50, 100, '<li.*?<h3><a.*?href="(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<p.*?>(.*?)<\\/p>', NULL, NULL, 1, 2, 3, NULL, 0);

--
-- Local search engine Israel(co.il)
--

INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.co.il', 'http://www.google.co.il/search?hl=iw&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0),
('bing.il', 'http://www.bing.com/search?q=[--keyword--]&scope=web&first=[--start--]&setmkt=[--lang--]-il', 'SRCHHPGUSR=NEWWND=0&NRSLT=50&SRCHLANG=[--lang--]', 50, 1, 50, 100, '<li.*?<h3><a.*?href="(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<p.*?>(.*?)<\\/p>', NULL, NULL, 1, 2, 3, NULL, 0);

--
-- Local search engine Malasya(com.my)
--

INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.com.my', 'http://www.google.com.my/search?hl=&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0),
('yahoo.my', 'http://malaysia.search.yahoo.com/search?p=[--keyword--]&n=[--num--]&b=[--start--]&vl=lang_[--lang--]&fl=1&v=1&vc=[--country--]', '', 100, 1, 100, 100, '<li.*?<h3><a.*?\\*\\*(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<div.*?>(.*?)<\\/div>.*?<\\/span>', NULL, NULL, 1, 2, 3, NULL, 0),
('bing.my', 'http://www.bing.com/search?q=[--keyword--]&scope=web&first=[--start--]&setmkt=[--lang--]-my', 'SRCHHPGUSR=NEWWND=0&NRSLT=50&SRCHLANG=[--lang--]', 50, 1, 50, 100, '<li.*?<h3><a.*?href="(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<p.*?>(.*?)<\\/p>', NULL, NULL, 1, 2, 3, NULL, 0);

--
-- Local search engine Norway(no)
--

INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.no', 'http://www.google.no/search?hl=no&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0),
('yahoo.no', 'http://no.search.yahoo.com/search?p=[--keyword--]&n=[--num--]&b=[--start--]&vl=lang_[--lang--]&fl=1&v=1&vc=[--country--]', '', 100, 1, 100, 100, '<li.*?<h3><a.*?\\*\\*(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<div.*?>(.*?)<\\/div>.*?<\\/span>', NULL, NULL, 1, 2, 3, NULL, 0),
('bing.no', 'http://www.bing.com/search?q=[--keyword--]&scope=web&first=[--start--]&setmkt=[--lang--]-no', 'SRCHHPGUSR=NEWWND=0&NRSLT=50&SRCHLANG=[--lang--]', 50, 1, 50, 100, '<li.*?<h3><a.*?href="(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<p.*?>(.*?)<\\/p>', NULL, NULL, 1, 2, 3, NULL, 0);

--
-- Local search engine netherlands(nl)
--

INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.nl', 'http://www.google.nl/search?hl=nl&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0),
('yahoo.nl', 'http://nl.search.yahoo.com/search?p=[--keyword--]&n=[--num--]&b=[--start--]&vl=lang_[--lang--]&fl=1&v=1&vc=[--country--]', '', 100, 1, 100, 100, '<li.*?<h3><a.*?\\*\\*(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<div.*?>(.*?)<\\/div>.*?<\\/span>', NULL, NULL, 1, 2, 3, NULL, 0),
('bing.nl', 'http://www.bing.com/search?q=[--keyword--]&scope=web&first=[--start--]&setmkt=[--lang--]-NL', 'SRCHHPGUSR=NEWWND=0&NRSLT=50&SRCHLANG=[--lang--]', 50, 1, 50, 100, '<li.*?<h3><a.*?href="(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<p.*?>(.*?)<\\/p>', NULL, NULL, 1, 2, 3, NULL, 0);



INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.co.id', 'http://www.google.co.id/search?hl=id&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0),
('yahoo.id', 'http://id.search.yahoo.com/search?p=[--keyword--]&n=[--num--]&b=[--start--]&vl=lang_[--lang--]&fl=1&v=1&vc=[--country--]', '', 100, 1, 100, 100, '<li.*?<h3><a.*?\\*\\*(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<div.*?>(.*?)<\\/div>.*?<\\/span>', NULL, NULL, 1, 2, 3, NULL, 0),
('bing.id', 'http://www.bing.com/search?q=[--keyword--]&scope=web&first=[--start--]&setmkt=[--lang--]-id', 'SRCHHPGUSR=NEWWND=0&NRSLT=50&SRCHLANG=[--lang--]', 50, 1, 50, 100, '<li.*?<h3><a.*?href="(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<p.*?>(.*?)<\\/p>', NULL, NULL, 1, 2, 3, NULL, 0);


--
-- Local search engine phillippines(ph)
--

INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.ph', 'http://www.google.com.php/search?hl=tl&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0),
('yahoo.ph', 'http://ph.search.yahoo.com/search?p=[--keyword--]&n=[--num--]&b=[--start--]&vl=lang_[--lang--]&fl=1&v=1&vc=[--country--]', '', 100, 1, 100, 100, '<li.*?<h3><a.*?\\*\\*(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<div.*?>(.*?)<\\/div>.*?<\\/span>', NULL, NULL, 1, 2, 3, NULL, 0),
('bing.ph', 'http://www.bing.com/search?q=[--keyword--]&scope=web&first=[--start--]&setmkt=[--lang--]-ph', 'SRCHHPGUSR=NEWWND=0&NRSLT=50&SRCHLANG=[--lang--]', 50, 1, 50, 100, '<li.*?<h3><a.*?href="(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<p.*?>(.*?)<\\/p>', NULL, NULL, 1, 2, 3, NULL, 0);

--
-- Local search engine newzeland(nz)
--

INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.co.nz', 'http://www.google.co.nz/search?hl=&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0),
('yahoo.nz', 'http://nz.search.yahoo.com/search?p=[--keyword--]&n=[--num--]&b=[--start--]&vl=lang_[--lang--]&fl=1&v=1&vc=[--country--]', '', 100, 1, 100, 100, '<li.*?<h3><a.*?\\*\\*(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<div.*?>(.*?)<\\/div>.*?<\\/span>', NULL, NULL, 1, 2, 3, NULL, 0),
('bing.nz', 'http://nz.bing.com/search?q=[--keyword--]&scope=web&first=[--start--]&setmkt=[--lang--]-nz', 'SRCHHPGUSR=NEWWND=0&NRSLT=50&SRCHLANG=[--lang--]', 50, 1, 50, 100, '<li.*?<h3><a.*?href="(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<p.*?>(.*?)<\\/p>', NULL, NULL, 1, 2, 3, NULL, 0);

--
-- Local search engine portugual(pt)
--

INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.pt', 'http://www.google.pt/search?hl=pt-PT&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0),
('bing.pt', 'http://www.bing.com/search?q=[--keyword--]&scope=web&first=[--start--]&setmkt=[--lang--]-PT', 'SRCHHPGUSR=NEWWND=0&NRSLT=50&SRCHLANG=[--lang--]', 50, 1, 50, 100, '<li.*?<h3><a.*?href="(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<p.*?>(.*?)<\\/p>', NULL, NULL, 1, 2, 3, NULL, 0);

--
-- Local search engine Singapore(sg)
--

INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.com.sg', 'http://www.google.com.sg/search?hl=&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0),
('yahoo.sg', 'http://sg.search.yahoo.com/search?p=[--keyword--]&n=[--num--]&b=[--start--]&vl=lang_[--lang--]&fl=1&v=1&vc=[--country--]', '', 100, 1, 100, 100, '<li.*?<h3><a.*?\\*\\*(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<div.*?>(.*?)<\\/div>.*?<\\/span>', NULL, NULL, 1, 2, 3, NULL, 0),
('bing.sg', 'http://www.bing.com/search?q=[--keyword--]&scope=web&first=[--start--]&setmkt=[--lang--]-SG', 'SRCHHPGUSR=NEWWND=0&NRSLT=50&SRCHLANG=[--lang--]', 50, 1, 50, 100, '<li.*?<h3><a.*?href="(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<p.*?>(.*?)<\\/p>', NULL, NULL, 1, 2, 3, NULL, 0);

--
-- Local search engine Taiwan(tw)
--

INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.com.tw', 'http://www.google.com.tw/search?hl=zh-TW&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0),
('yahoo.tw', 'http://tw.search.yahoo.com/search?p=[--keyword--]&n=[--num--]&b=[--start--]&vl=lang_[--lang--]&fl=1&v=1&vc=[--country--]', '', 100, 1, 100, 100, '<li.*?<h3><a.*?\\*\\*(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<div.*?>(.*?)<\\/div>.*?<\\/span>', NULL, NULL, 1, 2, 3, NULL, 0),
('bing.tw', 'http://www.bing.com/search?q=[--keyword--]&scope=web&first=[--start--]&setmkt=[--lang--]-tw', 'SRCHHPGUSR=NEWWND=0&NRSLT=50&SRCHLANG=[--lang--]', 50, 1, 50, 100, '<li.*?<h3><a.*?href="(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<p.*?>(.*?)<\\/p>', NULL, NULL, 1, 2, 3, NULL, 0);

--
-- Local search engine United Kingdom(uk)
--

INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.co.uk', 'http://www.google.co.uk/search?hl=&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0),
('yahoo.uk', 'http://uk.search.yahoo.com/search?p=[--keyword--]&n=[--num--]&b=[--start--]&vl=lang_[--lang--]&fl=1&v=1&vc=[--country--]', '', 100, 1, 100, 100, '<li.*?<h3><a.*?\\*\\*(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<div.*?>(.*?)<\\/div>.*?<\\/span>', NULL, NULL, 1, 2, 3, NULL, 0),
('bing.uk', 'http://www.bing.com/search?q=[--keyword--]&scope=web&first=[--start--]&setmkt=[--lang--]-gb', 'SRCHHPGUSR=NEWWND=0&NRSLT=50&SRCHLANG=[--lang--]', 50, 1, 50, 100, '<li.*?<h3><a.*?href="(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<p.*?>(.*?)<\\/p>', NULL, NULL, 1, 2, 3, NULL, 0);

--
-- Local search engine Vietnam(vn)
--

INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.com.vn', 'http://www.google.com.vn/search?hl=vi&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0),
('yahoo.vn', 'http://vn.search.yahoo.com/search?p=[--keyword--]&n=[--num--]&b=[--start--]&vl=lang_[--lang--]&fl=1&v=1&vc=[--country--]', '', 100, 1, 100, 100, '<li.*?<h3><a.*?\\*\\*(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<div.*?>(.*?)<\\/div>.*?<\\/span>', NULL, NULL, 1, 2, 3, NULL, 0);


--
-- Local search engine south africa(za)
--

INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.co.za', 'http://www.google.co.za/search?hl=&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0),
('bing.za', 'http://www.bing.com/search?q=[--keyword--]&scope=web&first=[--start--]&setmkt=[--lang--]-za', 'SRCHHPGUSR=NEWWND=0&NRSLT=50&SRCHLANG=[--lang--]', 50, 1, 50, 100, '<li.*?<h3><a.*?href="(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<p.*?>(.*?)<\\/p>', NULL, NULL, 1, 2, 3, NULL, 0);


INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.co.in', 'http://www.google.co.in/search?hl=en&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0),
('yahoo.in', 'http://in.search.yahoo.com/search?p=[--keyword--]&n=[--num--]&b=[--start--]&vl=lang_[--lang--]&fl=1&v=1&vc=[--country--]', '', 100, 1, 100, 100, '<li.*?<h3><a.*?\\*\\*(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<div.*?>(.*?)<\\/div>.*?<\\/span>', NULL, NULL, 1, 2, 3, NULL, 0),
('bing.in', 'http://www.bing.com/search?q=[--keyword--]&scope=web&first=[--start--]&setmkt=[--lang--]-in', 'SRCHHPGUSR=NEWWND=0&NRSLT=50&SRCHLANG=[--lang--]', 50, 1, 50, 100, '<li.*?<h3><a.*?href="(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<p.*?>(.*?)<\\/p>', NULL, NULL, 1, 2, 3, NULL, 0);

--
-- Local search engine argentina
--

INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.com.ar', 'http://www.google.com.ar/search?hl=es-419&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0),
('yahoo.ar', 'http://ar.search.yahoo.com/search?p=[--keyword--]&n=[--num--]&b=[--start--]&vl=lang_[--lang--]&fl=1&v=1&vc=[--country--]', '', 100, 1, 100, 100, '<li.*?<h3><a.*?\\*\\*(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<div.*?>(.*?)<\\/div>.*?<\\/span>', NULL, NULL, 1, 2, 3, NULL, 0),
('bing.ar', 'http://bing.com/search?q=[--keyword--]&scope=web&first=[--start--]&setmkt=[--lang--]-ar', 'SRCHHPGUSR=NEWWND=0&NRSLT=50&SRCHLANG=[--lang--]', 50, 1, 50, 100, '<li.*?<h3><a.*?href="(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<p.*?>(.*?)<\\/p>', NULL, NULL, 1, 2, 3, NULL, 0);


INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.com.ar', 'http://www.google.com.pa/search?hl=es&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0);

--
-- Local search engine poland
--

INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.pl', 'http://www.google.pl/search?hl=pl&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0),
('bing.pl', 'http://www.bing.com/search?q=[--keyword--]&scope=web&first=[--start--]&setmkt=[--lang--]-pl', 'SRCHHPGUSR=NEWWND=0&NRSLT=50&SRCHLANG=[--lang--]', 50, 1, 50, 100, '<li.*?<h3><a.*?href="(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<p.*?>(.*?)<\\/p>', NULL, NULL, 1, 2, 3, NULL, 0);

INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.co.th', 'http://www.google.co.th/search?hl=th&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0);

INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.gr', 'http://www.google.gr/search?hl=el&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0),
('yahoo.gr', 'http://gr.search.yahoo.com/search?p=[--keyword--]&n=[--num--]&b=[--start--]&vl=lang_[--lang--]&fl=1&v=1&vc=[--country--]', '', 100, 1, 100, 100, '<li.*?<h3><a.*?\\*\\*(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<div.*?>(.*?)<\\/div>.*?<\\/span>', NULL, NULL, 1, 2, 3, NULL, 0),
('bing.gr', 'http://www.bing.com/search?q=[--keyword--]&scope=web&first=[--start--]&setmkt=[--lang--]-gr', 'SRCHHPGUSR=NEWWND=0&NRSLT=50&SRCHLANG=[--lang--]', 50, 1, 50, 100, '<li.*?<h3><a.*?href="(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<p.*?>(.*?)<\\/p>', NULL, NULL, 1, 2, 3, NULL, 0);

INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.com.br', 'http://www.google.com.br/search?hl=pt-BR&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0),
('yahoo.br', 'http://br.search.yahoo.com/search?p=[--keyword--]&n=[--num--]&b=[--start--]&vl=lang_[--lang--]&fl=1&v=1&vc=[--country--]', '', 100, 1, 100, 100, '<li.*?<h3><a.*?\\*\\*(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<div.*?>(.*?)<\\/div>.*?<\\/span>', NULL, NULL, 1, 2, 3, NULL, 0),
('bing.br', 'http://www.bing.com/search?q=[--keyword--]&scope=web&first=[--start--]&setmkt=[--lang--]-br', 'SRCHHPGUSR=NEWWND=0&NRSLT=50&SRCHLANG=[--lang--]', 50, 1, 50, 100, '<li.*?<h3><a.*?href="(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<p.*?>(.*?)<\\/p>', NULL, NULL, 1, 2, 3, NULL, 0);


INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.cz', 'http://www.google.cz/search?hl=cs&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0);


INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.lt', 'http://www.google.lt/search?hl=lt&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0);

INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.se', 'http://www.google.se/search?hl=sv&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0),
('yahoo.se', 'http://se.search.yahoo.com/search?p=[--keyword--]&n=[--num--]&b=[--start--]&vl=lang_[--lang--]&fl=1&v=1&vc=[--country--]', '', 100, 1, 100, 100, '<li.*?<h3><a.*?\\*\\*(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<div.*?>(.*?)<\\/div>.*?<\\/span>', NULL, NULL, 1, 2, 3, NULL, 0),
('bing.se', 'http://www.bing.com/search?q=[--keyword--]&scope=web&first=[--start--]&setmkt=[--lang--]-se', 'SRCHHPGUSR=NEWWND=0&NRSLT=50&SRCHLANG=[--lang--]', 50, 1, 50, 100, '<li.*?<h3><a.*?href="(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<p.*?>(.*?)<\\/p>', NULL, NULL, 1, 2, 3, NULL, 0);


INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.bg', 'http://www.google.bg/search?hl=bg&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0),
('bing.bg', 'http://www.bing.com/search?q=[--keyword--]&scope=web&first=[--start--]&setmkt=[--lang--]-bg', 'SRCHHPGUSR=NEWWND=0&NRSLT=50&SRCHLANG=[--lang--]', 50, 1, 50, 100, '<li.*?<h3><a.*?href="(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<p.*?>(.*?)<\\/p>', NULL, NULL, 1, 2, 3, NULL, 0);


INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.co.jp', 'http://www.google.co.jp/search?hl=ja&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class="?g.*?<a.*?href="\\/url\\?q=(.*?)&amp;sa=U.*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="?ires"?>', '<\\/ol>', 1, 2, 3, NULL, 0),
('yahoo.co.jp', 'http://search.yahoo.co.jp/search?p=[--keyword--]&n=[--num--]&b=[--start--]&vl=lang_[--lang--]&fl=1&v=1&vc=[--country--]', '', 100, 1, 100, 100, '<li.*?<a.*?\\*\\*(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<\\/div>.*?<\\/li>', '<ol>', '<\\/ol>', 1, 2, 3, NULL, 0),
('www.bing.com', 'http://www.bing.com/search?q=[--keyword--]&scope=web&first=[--start--]&setmkt=ja-JP', 'SRCHHPGUSR=NEWWND=0&NRSLT=50&SRCHLANG=[--lang--]', 20, 1, 20, 100, '<li.*?<h3><a.*?href="(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<p.*?>(.*?)<\\/p>', NULL, NULL, 1, 2, 3, NULL, 0);

INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.at', 'http://www.google.at/search?hl=de&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0),
('yahoo.at', 'http://at.search.yahoo.com/search?p=[--keyword--]&n=[--num--]&b=[--start--]&vl=lang_[--lang--]&fl=1&v=1&vc=[--country--]', '', 100, 1, 100, 100, '<li.*?<h3><a.*?\\*\\*(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<div.*?>(.*?)<\\/div>.*?<\\/span>', NULL, NULL, 1, 2, 3, NULL, 0),
('bing.at', 'http://www.bing.com/search?q=[--keyword--]&scope=web&first=[--start--]&setmkt=[--lang--]-at', 'SRCHHPGUSR=NEWWND=0&NRSLT=50&SRCHLANG=[--lang--]', 50, 1, 50, 100, '<li.*?<h3><a.*?href="(.*?)".*?>(.*?)<\\/a><\\/h3>.*?<p.*?>(.*?)<\\/p>', NULL, NULL, 1, 2, 3, NULL, 0);


INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.bg', 'http://www.google.ro/search?hl=ro&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0);


INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.lk', 'http://www.google.lk/search?hl=en&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0);

INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES 
('google.si', 'http://www.google.si/search?hl=[--lang--]&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all&gws_rd=cr&nfpr=1&gws_rd=cr&nfpr=1&gws_rd=cr', '', '100', '0', '100', '100', '<div.*?class="?g.*?><h3 class="r"><a href="\\/url\\?q=(.*?)&amp;sa=U.*?>(.*?)<\\/a>.*?<\\/div><span.*?>(.*?)<\\/span>', '<div id="?ires"?>', '<\\/ol>', '1', '2', '3', NULL, 0);

INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.sk', 'http://www.google.sk/search?hl=sk&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0);

INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.com.eg', 'http://www.google.com.eg/search?hl=ar&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0);

INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.hu', 'http://www.google.hu/search?hl=hu&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0);

INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.com.co', 'http://www.google.com.co/search?hl=es&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0);


INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.ee', 'http://www.google.ee/search?hl=et&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0);

INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.iq', 'http://www.google.iq/search?hl=ar&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0);

INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.ae', 'http://www.google.ae/search?hl=ar&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0);

INSERT INTO `searchengines` (`domain`, `url`, `cookie_send`, `no_of_results_page`, `start`, `start_offset`, `max_results`, `regex`, `from_pattern`, `to_pattern`, `url_index`, `title_index`, `description_index`, `encoding`, `status`) VALUES
('google.ng', 'http://www.google.ng/search?hl=yo&num=[--num--]&q=[--keyword--]&start=[--start--]&cr=country[--country--]&as_qdr=all', '', 100, 0, 100, 100, '<li.*?class=g.*?<a.*?href="(.*?)".*?>(.*?)<\\/a>.*?<div.*?>(.*?)<cite>', '<div id="ires">', '<\\/ol>', 1, 2, 3, NULL, 0);


UPDATE searchengines SET from_pattern='<div id="?ires"?>' where  url LIKE '%google%';                                                                                               
UPDATE searchengines SET regex = '<li.*?<h2><a.*?href="(.*?)".*?>(.*?)<\\/a><\\/h2>.*?<p.*?>(.*?)<\\/p>' WHERE url like '%bing.com%';
UPDATE searchengines SET `cookie_send` = 'sB=v=1&n=100&sh=1&rw=new', `regex` = '<li.*?<h3.*?><a.*?RU=(.*?)\\/.*?>(.*?)<\\/a><\\/h3>.*?<p.*?>(.*?)<\\/p>'   WHERE url like '%yahoo%';

UPDATE `searchengines` SET url = CONCAT(url, "&gws_rd=cr") WHERE `url` LIKE '%google%';
UPDATE `searchengines` SET `regex` = '<div.*?class=\"?g.*?>.*?<div.*?class=\"r.*?\">.*?<a href=\"(.*?)\".*?>.*?<h3.*?>(.*?)<\\/h3>' WHERE `url` LIKE '%google%';

