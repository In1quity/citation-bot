<?php
declare(strict_types=1);
// Some data we get from outside sources is bad or at least mis-defined
// Use lower case for all of these, and then compare to a lower cased version
const HAS_NO_VOLUME = array("zookeys", "studia hibernica", "analecta hibernica", "british art studies", "der spiegel",
                            "international astronomical union circular", "yale french studies", "capjournal",
                            "cap journal", "phytokeys", "starinar", "balcanica", "american museum novitates",
                            "european journal of taxonomy");  // Some journals have issues only, no volume numbers
const HAS_NO_ISSUE = array("special papers in palaeontology");  // Some journals have volumes only, no issue numbers
const BAD_ACCEPTED_MANUSCRIPT_TITLES = array("oup accepted manuscript", "placeholder for bad pdf file", 
                                             "placeholder", "symbolic placeholder", "[placeholder]", 
                                             "placeholder for arabic language transliteration", "article not found");
const BAD_AUTHORS = array("unknown", "missing", "- -.", "- -", "no authorship indicated", "no authorship", "no author",
                           "no authors", "no author indicated", "no authorship indicated", "dk eyewitness", "united states",
                           "great britain", "indiatoday", "natural history museum bern");
const NON_HUMAN_AUTHORS = array('collaborat', 'reporter', 'journalist', 'correspondent', 'anchor', 'staff', 'foreign',
                                'national', 'endowment', ' for the ', 'humanities', 'committee', 'group',
                                'society', ' of america', 'association', ' at the ', 'board of ', 'communications',
                                'corporation', 'incorporated', 'editorial', 'university', 'dept. of', 'department',
                                'dept of ', 'college', 'center for', 'office of', 'editor', 
                                'world news', 'national news', 'eyewitness', 'information', 'business', 'bureau',
                                'us census', 'indiatoday', 'natural history', 'museum');
const BAD_PUBLISHERS = array('london', 'edinburgi', 'edinburgh', 'no publisher', 'no publisher given',
                             'no publisher specified', 'unknown', 'publisher not identified', 'report');

const ARE_WORKS = array('medrxiv'); // Things with dois that should be {{cite document|work=THIS}}

const PUBLISHERS_ARE_WORKS = array('the san diego union-tribune', 'forbes', 'salon', 'san jose mercury news', 'san jose mercury-news', 'new york times',
                                   'the new york times', 'daily news online', 'daily news', 'the sun', 'the times',
                                   'the star', 'washington post', 'the washington post', 'the tribune',
                                   'los angeles times', 'la times', 'the la times', 'htmlgiant', 'the los angeles times',
                                   'sandiegouniontribune.com', 'forbes.com', 'salon.com', 'mercurynews.com', 'nytimes.com',
                                   'thedailynewsonline.com', 'thesun.com', 'thetimes.com', 'thestar.com',
                                   'washingtonpost.com', 'thetribune.com', 'latimes.com', 'htmlgiant.com',
                                   'the guardian', 'fox sports', 'mlb.com', 'espn.com', 'forbes media', 'forbes online',
                                   'cbs sports', 'national journal', 'foxnews', 'the hill', 'nationaljournal.com',
                                   'the huffington post', 'the times digital archive', 'belmontstakes.com', 'the times archives',
                                   'new york times.com', 'news shopper', 'birmingham post', 'the independent',
                                   'rediff.com', 'squashplayer.co.uk', 'fixtures live', 'the star online',
                                   'oneindia', 'international business times', 'the hindu', 'daily news and analysis',
                                   'nfl.com', 'foxsports.com', 'the new yorker', 'findlaw.com', 'newsmax',
                                   'washtimes.com', 'washington times', 'findlaw', 'new york times magazine',
                                   'stripes', 'arizona daily star', 'the times of india', 'the times-news', 'san diego union tribune',
                                   'the star (malaysia)', 'utusan malaysia', 'daily news, sri lanka', 'daily news & analysis',
                                   'new york daily news', 'new york daily news', 'daily news (new york)', 
                                   'anchorage daily news', 'palm beach daily news', 'daily news egypt', 'the daily news egypt',
                                   'daily news latino', 'forbes méxico', 'forbes mexico', 'forbes india', 'forbesmiddleeast',
                                   'forbes middle east', 'forbes russia', 'forbes.ru', 'forbes afrique', 'forbes magazine',
                                   'forbes asia', 'forbes israel', 'forbes global 2000', 'forbes china', '[[forbes]] (Russia)',
                                   'forbes việt nam', 'forbes vietnam', 'forbes viet nam', 'forbes contributor blogs',
                                   'the baltimore sun'
                                   // WP:CITALICSRFC and MOS:ITALICWEBCITE  ?????     'abc news', 'nbc news', 'cbs news', 'bbc news'
                                  ); // LOWER CASE!  WWW not there too! 

const WORKS_ARE_PUBLISHERS = array('united states census bureau'); // LOWER CASE!

const DUBIOUS_JOURNALS = array('fda', 'reuters', 'associated press', 'ap', 'ap wire', 'report'); // Things we add, but only if publisher and agency are both blank

const NATURE_FAILS = TRUE;  // Nature dropped the ball for now TODO - https://dx.doi.org/10.1111/j.1572-0241.2006.00844.x should point to https://journals.lww.com/ajg/Abstract/2006/11000/Proton_Pump_Inhibitor_Therapy_for_Suspected.31.aspx

// Catch 'authors' such as "hearst magazines", "time inc", "nielsen business media, inc"
// Ordered alphabetically.
const PUBLISHER_ENDINGS = ["books", "corporation", 'centre', 'center', 'company', "inc.", "inc", "magazines",
                           'museum', "press", "publishers", "publishing", 'science'];
const BAD_TITLES = array("unknown", "missing", "arxiv e-prints", "arxiv mathematics e-prints", 
                         "ssrn electronic journal", "dissertations available from proquest",
                         "ebscohost login",  "library login", "google groups", "sciencedirect", "cur_title",
                         "wordpress › error", "ssrn temporarily unavailable", "log in - proquest",
                         "shibboleth authentication request", "nookmarkable url intermediate page",
                         "google books", "rte.ie", "loading", "google book",
                         "the article you have been looking for has expired and is not longer available on our system. this is due to newswire licensing terms.",
                         "openid transaction in progress", 'download limit exceeded', 'privacy settings',
                         "untitled-1", "untitled-2", "professional paper", "zbmath",
                         "theses and dissertations available from proquest", "proquest ebook central", "report",
                         "bloomberg - are you a robot?", "page not found",
                         "breaking news, analysis, politics, blogs, news photos, video, tech reviews",
                         "breaking news, analysis, politics, blogs, news photos, video, tech reviews - time.com",
                         "redirect notice", "oxford music online"
                        );
const IN_PRESS_ALIASES = array("in press", "inpress", "pending", "published", 
                               "published online", "no-no", "n/a", "online ahead of print", 
                               "unpublished", "unknown", "tba", "forthcoming", "in the press", 
                               "na", "submitted", "tbd", "missing");
const NON_JOURNAL_BIBCODES = array('arXiv', 'gr.qc', 'hep.ex', 'hep.lat', 'hep.ph', 'hep.th', 'astro.ph',
                                   'math', 'nucl.ex', 'nucl.th', 'physics', 'quant.ph', 'alg.geom',
                                   'cond.mat', 'cs.', 'econ.', 'eess.', 'nlin.');
const NON_PUBLISHERS = ['books.google', 'google books', 'google news', 'google.co', 'google book',
                        'zenodo', 'archive.org', 'citeseerx.ist.psu.edu', 'archive.fo', 'archive.today',
                        'hdl.handle.net', 'pub med', 'researchgate']; // Google Inc is a valid publisher, however.
const BAD_ZOTERO_TITLES = ['Browse publications', 'Central Authentication Service', 'http://', 'https://',
                                 'ZbMATH - the first resource for mathematics', 'MR: Matches for:',
                                 ' Log In', 'Log In ', 'Sign in', 'Bookmarkable URL intermediate page', 'Shibboleth Authentication Request',
                                 'domain for sale', 'website for sale', 'domain is for sale', 'website is for sale',
                                 'lease this domain', 'domain available', 'metaTags', 'An Error Occurred', 'User Cookie',
                                 'Cookies Disabled', 'page not found', '411 error', 'url not found',
                                 'limit exceeded', 'Error Page', '}}', '{{', 'EU Login', 'bad gateway', 'Captcha',
                                 '.com', '.gov', '.org', 'View PDF', 'Wayback Machine', 'does not exist', 
                                 'Subscribe to read', 'Wiley Online Library', 'pagina is niet gevonden',
                                 'Zoeken in over NA', 'na een 404', '404 error', 'Account Suspended',
                                 'Error 404', 'EZProxy', 'EBSCOhost Login', '404 - Not Found', '404!',
                                 'Temporarily Unavailable', ' has expired', 'not longer available',
                                 'Article expired', 'This is due to newswire licensing terms',
                                 'OpenId transaction in progress', 'Download Limit Exceeded', 'Internet Archive Wayback Machine',
                                 'Url（アドレス）が変わりました', '404エラ', 'お探しのページは見つかりませんでした',
                                 'privacy settings', 'cookie settings', 'WebCite query', 'Ой!',
                                 'Untitled-1', 'Untitled-2', 'Untitled-3', 'Untitled-4', 'Untitled-5',
                                 'Untitled-6', 'Untitled-7', 'Untitled-8', 'Untitled-9', 'Are you a robot',
                                 'Aanmelden of registreren om te bekijken', 'register to view', 'being redirected',
                                 'has been registered', 'Aanmelden bij Facebook', 'Einloggen', 'The Times & the Sunday Times',
                                 'Login • Instagram', 'subscriber to read'];

const CANONICAL_PUBLISHER_URLS = array ('elsevier.com', 'springer.com', 'sciencedirect.com', 'tandfonline.com',
                                'taylorandfrancis.com', 'wiley.com', 'sagepub.com', 'sagepublications.com',
                                'scielo.org', 'scielo.br', 'degruyter.com', 'hindawi.com', 'inderscience.com',
                                'cambridge.org', '.oup.com', 'nature.com', 'macmillan.com', 'ieeexplore.ieee.org',
                                'worldscientific.com', 'iospress.com', 'iospress.nl', 'pnas.org', 'journals.ametsoc.org',
                                'pubs.geoscienceworld.org', 'pubs.rsc.org', 'journals.uchicago.edu',
                                'annualreviews.org', 'aip.scitation.org', 'psyche.entclub.org', 'thelancet.com',
                                'amjbot.org', 'gsapubs.org', 'jwildlifedis.org', 'msptm.org', 'nrcresearchpress.',
                                'fundacionmenteclara.org.ar', 'iopscience.iop.org', 'bmj.com/cgi/pmidlookup',
                                'sciencemag.org', 'doi.apa.org', 'psycnet.apa.org', 'journals.upress.ufl.edu',
                                'clinchem.org', 'cell.com', 'aeaweb.org', 'chestpubs.org', 'journal.chestnet.org',
                                'chestjournal.org', 'biomedcentral.com', 'journals.royalsociety.org',
                                'mdpi.com', 'frontiersin.org',
                                //  Below are journal search engines
                                '.serialssolutions.com', '.ebscohost.com',
                                //  Below are proxys
                                'proxy.libraries', 'proxy.lib.', '.ezproxy.', '-ezproxy.', '/ezproxy.',
                                //  Below are sites that are simply DOI resolvers, like dx.doi.org
                                'doi.library.ubc.ca');

const PROXY_HOSTS_TO_ALWAYS_DROP = array('proxy.libraries', 'proxy.lib.', '.ezproxy.', '-ezproxy.', '/ezproxy.',
                                  '.serialssolutions.com', 'search.ebscohost.com', 'findarticles.com', 'journals.royalsociety.org'); // Drop these if there is a valid DOI

const PROXY_HOSTS_TO_DROP = array('proxy.libraries', 'proxy.lib.', '.ezproxy.', '-ezproxy.', '/ezproxy.',
                                  '.serialssolutions.com', '.ebscohost.com', 'linkinghub.elsevier.com',
                                  'doi.library.ubc.ca', 'ingentaconnect.com/content', 'sciencedirect.com/science?_ob',
                                  'informaworld.com/smpp', '.search.serialssolutions.com', 'doi.apa.org',
                                  'onlinelibrary.wiley.com/resolve/openurl', 'findarticles.com', 'psycnet.apa.org'); // Drop these if there is a valid FREE DOI

const WEB_NEWSPAPERS = array('bbc news', 'bbc', 'news.bbc.co.uk', 'bbc sports', 'bbc sport', 'www.bbc.co.uk', 'the economist');

const NO_DATE_WEBSITES = array('wikipedia.org', 'web.archive.org', 'perma-archives.org', 'webarchive.proni.gov.uk', 'perma.cc',
                              'wayback', 'web.archive.bibalex.org', 'web.petabox.bibalex.org', 'webharvest.gov', 'archive.wikiwix.com',
                              'archive.is', 'archive-it.org', 'nationalarchives.gov.uk', 'freezepage.com', 'webcitation.org',
                              'waybackmachine.org', 'siarchives.si.edu', 'gutenberg.org', 'archive.fo', 'archive.today',
                              'oireachtas.ie');

const ZOTERO_AVOID_REGEX = array("twitter\.",               // This should be {{cite tweet}}
                                 "youtube\.", "youtu\.be",  // This should be {{cite AV media}}
                                 "books\.google\.",         // We have special google books code
                                 "google\.com/search",      // Google search results
                                 "jstor\.org/stable/",      // We have special jstor code
                                 "ned\.ipac\.caltech\.edu", // Gives no real title
                                 "pep\-web\.org",           // Does not parse very well at all
                                 "ezproxy", "arkive\.org", "bloomberg\.com/tosv2.html",  // Junk
                                 "worldcat\.org",           // Should use parameters and google instead
                                 "kyobobook\.co\.kr",       // Bookstore that give junk
                                 "facebook\.com",           // login and junk
                                 "leighrayment\.com",       // highjacked
                                 "seapower\-digital\.com",  // highjacked
                                 "scholarlycommons\.pacific\.edu\/euler\-works", // returns journal reference to works, not the actual work
                                 "miar\.ub\.edu\/issn", // ISSN description, not actually the journal
                                 );
const NON_JOURNAL_WEBSITES = array('cnn.com/', 'foxnews.com/', 'msnbc.com/', 'nbcnews.com/', 'abcnews.com/', 'cbs.com/', 
                                   'cbsnews.com/', 'abc.com/', 'bbc.com/', 'bbc.co.uk/', 'apnews.com/',
                                   '.ap.org/', 'nytimes.com/', 'theguardian.com/', 'washingtonpost.com/',
                                   'newyorker.com/', 'independent.co.uk/', 'cnbc.com/', 'vanityfair.com/',
                                   'theatlantic.com/', '-news.co.uk/', 'news.google.com/', 'jpl.nasa.gov/',
                                   'gsfc.nasa.gov/', 'solarsystem.nasa.gov/', 'latimes.com/',
                                   'usatoday.com/', 'wsj.com/', 'haaretz.com/', 'buzzfeed.com/',
                                   'aljazeera.com/', 'vox.com/', 'reuters.com/', 'dailynews.com/', 
                                   'newsweek.com/', 'monitor.com/', 'observer.com/', '.pbs.org/', '.bbm.ca/', '/bbm.ca/',
                                   'mediaincanada.com/', 'cbspressexpress.com/', 'deadline.com/', 'zap2it.com/',
                                   'yourentertainmentnow.com/', 'shows.ctv.ca/' ,'toronto.com/', 'fda.gov/',
                                   'surgeongeneral.gov/', 'www.rte.ie/', 'plato.stanford.edu/', 'britannica.com/',
                                   'gameinformer.com/', 'mcvuk.com/', 'rockpapershotgun.com/',
                                   'lemonde.fr/', '.ft.com/', 'bloomberg.com/', 'pcgamer.com/', 'mynintendonews.com/',
                                   'arstechnica.com/', 'bluesnews.com/', 'gamingbolt.com/', 'nintendoworldreport.com/',
                                   'pcgamesn.com/', 'pocketgamer.com/', 'purexbox.com/', 'pushsquare.com/',
                                   'toucharcade.com/', 'adventuregamers.com/', 'justadventure.com/', 'rpgamer.com/',
                                   'rpgfan.com/', 'rpgsite.net/', 'wargamer.com/', 'scmp.com/', 'cnet.com/',
                                   'engadget.com/', 'techradar.com/', 'dotesports.com/', 'sportsbusinessjournal.com/',
                                   'heroesneverdie.com/', 'invenglobal.com/', 'metabomb.net/', 'redbull.com/',
                                   'theflyingcourier.com/', 'riftherald.com/', 'upcomer.com/', 'metacritic.com/',
                                   'mlg.com/', 'majornelson.com/', 'primagames.com/', 'playstation.com/',
                                   'digitallydownloaded.net/', 'easyallies.com/', 'gamesradar.com/', 'egmnow.com/',
                                   'eurogamer.net/', 'gameinformer.com/', 'gamerevolution.com/', 'gamespot.com/',
                                   'gamezone.com/', 'gamesradar.com/', 'gematsu.com/', 'hardcoregamer.com/',
                                   'hardcoregaming101.net/', '.ign.com/', 'kotaku.com/', 'polygon.com/',
                                   'siliconera.com/', 'shacknews.com/', '.svg.com/', 'variety.com/', 'venturebeat.com/',
                                   'vg247.com/', 'videogameschronicle.com/', 'videogamer.com/', '4gamer.net/',
                                   'comptiq.com/', 'dengekionline.com/', 'famitsu.com/', 'gamekult.com/',
                                   'gameswirtschaft.de/', 'gamer.nl/', 'thegamesmachine.it/', 'hobbyconsolas.com/',
                                   'watch.impress.co.jp/', 'itmedia.co.jp/', 'jeuxvideo.com/', 'm-create.com/',
                                   'pu.nl/', 'digit.in/', 'chart-track.co.uk/', 'elspa.com/', 'gamedeveloper.com/',
                                   'gamesindustry.biz/', 'gamestudies.org/', 'mcvuk.com/', 'the-magicbox.com/',
                                   'amr.abime.net/', 'gamestar.de/', 'arcade-museum.com/', 'nintendoforcemagazine.com/',
                                   'nintendolife.com/', 'denofgeek.com/', '112.international/', '112.ua/', 'aa.com.tr/',
                                   'abcnews.com/', 'abcnews.go.com/', 'about.com/', 'adfontesmedia.com/', 'adl.org/',
                                   'afp.com/', 'aljazeera.com/', 'aljazeera.net/', 'allgame.com/', 'allmovie.com/',
                                   'allmusic.com/', 'alternet.org/', 'amazon.ca/', 'amazon.cn/', 'amazon.co.jp/',
                                   'amazon.co.uk/', 'amazon.com/', 'amazon.com.au/', 'amazon.com.br/', 'amazon.com.mx/',
                                   'amazon.com.sg/', 'amazon.com.tr/', 'amazon.de/', 'amazon.es/', 'amazon.fr/',
                                   'amazon.in/', 'amazon.it/', 'amazon.nl/', 'ancestry.com/', 'aninews.in/',
                                   'anphoblacht.com/', 'answers.com/', 'antwerpen-indymedia.be/', '/ap.org/', '.ap.org/',
                                   'parliament.uk/', 'apnews.com/', 'arabnews.com/', 'armeniasputnik.am/',
                                   'arstechnica.co.uk/', 'arstechnica.com/', 'askmen.com/', 'askubuntu.com/',
                                   'avclub.com/', 'avn.com/', 'axios.com/', 'b.baidu.com/', 'baike.baidu.com/',
                                   'ballotpedia.org/', 'banned.video/', 'bbc.co.uk/', 'bbc.com/', 'bellingcat.com/',
                                   'bestgore.com/', 'bharat.republicworld.com/', 'bigmuddyimc.org/', 'bild.de/',
                                   'biography.com/', 'blogspot.com/', 'bloomberg.com/', 'boingboing.net/',
                                   'breitbart.com/', 'britannica.com/', 'burkespeerage.com/', 'businessinsider.com/',
                                   'businessweek.com/', 'bustle.com/', 'buzzfeed.com/', 'buzzfeednews.com/',
                                   'californiaglobe.com/', 'catalyst-journal.com/', 'cato.org/', 'celebritynetworth.com/',
                                   'cepr.net/', 'cesnur.net/', 'cgtn.com/', 'chinadaily.com.cn/', 'chinadailyasia.com/',
                                   'chinadailyhk.com/', 'cinemos.com/', 'cliffsnotes.com/', 'climatefeedback.org/',
                                   'cmhansrd/', 'cnet.com/', 'cnn.com/', 'cnsnews.com/', 'codastory.com/', 'coindesk.com/',
                                   'commonsensemedia.org/', 'company-histories.com/', 'conservativereview.com/',
                                   'consortiumnews.com/', 'cosmopolitan.com/', 'council.rollingstone.com/',
                                   'counterpunch.org/', 'cracked.com/', 'crunchbase.com/', 'csmonitor.com/',
                                   'dailycaller.com/', 'dailycallernewsfoundation.org/', 'dailydot.com/',
                                   'dailykos.com/', 'dailym.ai/', 'dailymail.co.uk/', 'dailymail.com/', 'dailymail.com.au/',
                                   'dailysabah.com/', 'dailystar.co.uk/', 'dailywire.com/', 'deadline.com/',
                                   'deadlinehollywooddaily.com/', 'debretts.com/', 'democracynow.org/', 'deseretnews.com/',
                                   'digitalspy.co.uk/', 'digitalspy.com/', 'discogs.com/', 'dreamteamfc.com/',
                                   'economist.com/', 'electronicintifada.net/', 'engadget.com/', 'entrepreneur.com/',
                                   'epochtimes.com/', 'ew.com/', 'examiner.com/', 'express.co.uk/', 'facebook.com/',
                                   'fair.org/', 'familysearch.org/', 'famousbirthdays.com/', 'fandom.com/',
                                   'findagrave.com/', 'findarticles.com/', 'findmypast.co.uk/', 'flickr.com/',
                                   'forbes.com/', 'foxbusiness.com/', 'foxnews.com/', 'frontpagemag.com/',
                                   'frontpagemagazine.com/', 'ft.com/', 'fundinguniverse.com/', 'gamasutra.com/',
                                   'gameinformer.com/', 'gamerankings.com/', 'gawker.com/', 'geni.com/', 'genius.com/',
                                   'gizmodo.com/', 'glitchwave.com/', 'globalresearch.ca/', 'globalresearch.org/',
                                   'globalsecurity.org/', 'globaltimes.cn/', 'goodreads.com/', 'google.com/', 'grubstreet.com/',
                                   'guardian.co.uk/', 'guinnessworldrecords.com/', 'haaretz.co.il/', 'haaretz.com/',
                                   'hansard.millbanksystems.com/', 'hansard.parliament.uk/', 'heatst.com/', 'heavy.com/',
                                   'hispantv.com/', 'hispantv.ir/', 'history.com/', 'hk.appledaily.com/', 'hollywoodreporter.com/',
                                   'hopenothate.org.uk/', 'screenrant.com/', 'huanqiu.com/', 'huffingtonpost.ca/',
                                   'huffingtonpost.co.uk/', 'huffingtonpost.com/', 'huffingtonpost.com.au/',
                                   'huffingtonpost.com.mx/', 'huffingtonpost.de/', 'huffingtonpost.es/', 'huffingtonpost.fr/',
                                   'huffingtonpost.gr/', 'huffingtonpost.in/', 'huffingtonpost.it/', 'huffingtonpost.jp/',
                                   'huffingtonpost.kr/', 'huffpost.com/', 'huffpostbrasil.com/', 'huffpostmaghreb.com/',
                                   'humanevents.com/', 'ibtimes.co.in/', 'ibtimes.co.uk/', 'ibtimes.com/', 'ibtimes.com.au/',
                                   'ibtimes.com.cn/', 'ibtimes.sg/', 'idolator.com/', 'ifcncodeofprinciples.poynter.org/',
                                   'ign.com/', 'ijr.com/', 'imc-africa.mayfirst.org/', 'imdb.com/', 'independent.co.uk/',
                                   'indianexpress.com/', 'indybay.org/', 'indymedia.ie/', 'indymedia.nl/', 'indymedia.no/',
                                   'indymedia.org/', 'indymedia.org.uk/', 'indymedia.us/', 'indymediapr.org/',
                                   'infowars.com/', 'infowars.net/', 'infowars.tv/', 'inquisitr.com/', 'investopedia.com/',
                                   'ipscuba.net/', 'ipsnews.net/', 'ipsnoticias.net/', 'iranicaonline.org/',
                                   'jacobinmag.com/', 'jewishvirtuallibrary.org/', 'jezebel.com/', 'jihadwatch.org/',
                                   'journalism.org/', 'knowyourmeme.com/', 'kommersant.com/', 'kommersant.ru/',
                                   'kommersant.uk/', 'last.fm/', 'latimes.com/', 'ldhansrd/', 'lenta.ru/', 'lifesite.net/',
                                   'lifesitenews.com/', 'lifewire.com/', 'linkedin.com/', 'livejournal.com/', 'liveleak.com/',
                                   'lulu.com/', 'mailonline.pressreader.com/', 'mailonsunday.co.uk/',
                                   'mailpictures.newsprints.co.uk/', 'mailplus.co.uk/', 'maps.google.com/',
                                   'marquiswhoswho.com/', 'mashable.com/', 'mathoverflow.net/', 'mediabiasfactcheck.com/',
                                   'mediaite.com/', 'mediamatters.org/', 'medium.com/', 'memri.org/', 'memritv.org/',
                                   'metacritic.com/', 'metal-archives.com/', 'metal-experience.com/', 'metalsucks.net/',
                                   'metro.co.uk/', 'metro.news/', 'mg.co.za/', 'michiganimc.org/', 'midiaindependente.org/',
                                   'mintpressnews.com/', 'mirror.co.uk/', 'monde-diplomatique.fr/', 'mondediplo.com/',
                                   'mondialisation.ca/', 'mondoweiss.net/', 'morningstaronline.co.uk/', 'motherjones.com/',
                                   'mrc.org/', 'mrctv.org/', 'msnbc.com/', 'mylife.com/', 'nationalenquirer.com/',
                                   'nationalgeographic.com/', 'nationalreview.com/', 'naturalnews.com/', 'nbcnews.com/',
                                   'newrepublic.com/', 'news.cn/', 'newsblaze.com/', 'newsblaze.com.au/', 'newsbreak.com/',
                                   'newsbusters.org/', 'newscientist.com/', 'newsmax.com/', 'newsmaxtv.com/',
                                   'newsoftheworld.co.uk/', 'newsoftheworld.com/', 'newstarget.com/', 'newswars.com/',
                                   'newsweek.com/', 'newyorker.com/', 'nndb.com/', 'npr.org/', 'nydailynews.com/', 'nymag.com/',
                                   'nypost.com/', 'nytimes.com/', 'nzherald.co.nz/', 'oann.com/', 'occupydemocrats.com/',
                                   'opindia.com/', 'opindia.in/', 'order-order.com/', 'ourcampaigns.com/', 'page3.com/',
                                   'pagesix.com/', 'panampost.com/', 'parliament.uk/', 'patheos.com/', 'people-press.org/',
                                   'people.com/', 'pewforum.org/', 'pewglobal.org/', 'pewhispanic.org/', 'pewinternet.org/',
                                   'pewresearch.org/', 'pewsocialtrends.org/', 'phillyimc.org/', 'pinknews.co.uk/',
                                   'playboy.com/', 'politico.com/', 'politifact.com/', 'polygon.com/', 'pressreader.com/',
                                   'presstv.co.uk/', 'presstv.com/', 'presstv.ir/', 'presstv.tv/', 'pride.com/',
                                   'prnewswire.co.uk/', 'prnewswire.com/', 'projectveritas.com/', 'propublica.org/',
                                   'quackwatch.org/', 'quadrant.org.au/', 'quillette.com/', 'quora.com/', 'qz.com/',
                                   'rapgenius.com/', 'rappler.com/', 'rateyourmusic.com/', 'rawstory.com/', 
                                   'realclearinvestigations.com/', 'realclearpolitics.com/', 'reason.com/', 'reddit.com/',
                                   'redstate.com/', 'refinery29.com/', 'religionnews.com/', 'republicworld.com/',
                                   'reunion.com/', 'reuters.com/', 'rfa.org/', 'rian.com.ua/', 'rian.ru/', 'rogueimc.org/',
                                   'rollingstone.com/', 'rottentomatoes.com/', 'rt.com/', 'russiatoday.com/', 'salon.com/',
                                   'sbs.com.au/', 'sciencebasedmedicine.org/', 'scienceblogs.com/', 'scientificamerican.com/',
                                   'scmp.com/', 'scotusblog.com/', 'scribd.com/', 'searchlightmagazine.com/', 'serverfault.com/',
                                   'sherdog.com/', 'sixthtone.com/', 'skepdic.com/', 'skwawkbox.org/', 'slate.com/',
                                   'slate.fr/', 'smh.com.au/', 'snopes.com/', 'softpedia.com/', 'sonemic.com/',
                                   'sourcewatch.org/', 'sparknotes.com/', 'spectator.co.uk/', 'spectator.us/',
                                   'spiegel.de/', 'spirit-of-metal.com/', 'splcenter.org/', 'sputnik-abkhazia.ru/',
                                   'sputnik-georgia.com/', 'sputnik-georgia.ru/', 'sputnik-ossetia.com/', 'sputnik-ossetia.ru/',
                                   'sputnik-tj.com/', 'sputnik.az/', 'sputnik.by/', 'sputnik.kg/', 'sputnik.kz/',
                                   'sputnik.md/', 'sputniknews-uz.com/', 'sputniknews.cn/', 'sputniknews.com/',
                                   'sputniknews.gr/', 'sputniknews.lt/', 'sputniknews.ru/', 'sputniknewslv.com/',
                                   'stackexchange.com/', 'stackoverflow.com/', 'standard.co.uk/', 'starsunfolded.com/',
                                   'straitstimes.com/', 'sunnation.co.uk/', 'superuser.com/', 'swarajyamag.com/',
                                   'takimag.com/', 'tass.com/', 'tass.ru/', 'techcrunch.com/', 'telegraph.co.uk/',
                                   'telesurenglish.net/', 'telesurtv.net/', 'the-sun.com/', 'theage.com.au/',
                                   'theamericanconservative.com/', 'theatlantic.com/', 'theaustralian.com.au/',
                                   'thebalance.com/', 'theblaze.com/', 'thecanary.co/', 'theconversation.com/',
                                   'thecut.com/', 'thedailybeast.com/', 'thediplomat.com/', 'theepochtimes.com/',
                                   'thefederalist.com/', 'thegatewaypundit.com/', 'theglobeandmail.com/', 'thegrayzone.com/',
                                   'thegreenpapers.com/', 'theguardian.co.uk/', 'theguardian.com/', 'thehill.com/',
                                   'thehindu.com/', 'theintercept.com/', 'thejc.com/', 'themarysue.com/', 'thenation.com/',
                                   'theneedledrop.com/', 'thenewamerican.com/', 'thenextweb.com/', 'theonion.com/',
                                   'thepointsguy.com/', 'thepostmillennial.com/', 'theregister.co.uk/', 'thescottishsun.co.uk/',
                                   'thespruce.com/', 'thestar.ie/', 'thesun.co.uk/', 'thesun.ie/', 'thesun.mobi/',
                                   'thesundaytimes.co.uk/', 'thetimes.co.uk/', 'thetruthaboutguns.com/', 'theverge.com/',
                                   'thewrap.com/', 'thinkprogress.org/', 'thisisinsider.com/', 'thisismoney.co.uk/',
                                   'thoughtco.com/', 'time.com/', 'timesofindia.com/', 'timesofindia.indiatimes.com/',
                                   'timesonline.co.uk/', 'tmz.com/', 'tnimc.org/', 'torrentfreak.com/', 'townhall.com/',
                                   'travelmail.co.uk/', 'tripsavvy.com/', 'trtworld.com/', 'TV.com/', 'tvguide.com/',
                                   'tvguidemagazine.com/', 'tvtropes.org/', 'twitter.com/', 'ucimc.org/', 'ukwhoswho.com/',
                                   'unz.com/', 'unz.org/', 'urbandictionary.com/', 'usatoday.com/', 'usmagazine.com/',
                                   'usnews.com/', 'vanityfair.com/', 'variety.com/', 'vdare.com/', 'venezuelanalysis.com/',
                                   'venturebeat.com/', 'verywell.com/', 'verywellfamily.com/', 'verywellhealth.com/',
                                   'verywellmind.com/', 'veteranstoday.com/', 'vgchartz.com/', 'vice.com/', 'vicetv.com/',
                                   'vogue.com/', 'voiceofrussia.com/', 'voltairenet.org/', 'vox.com/', 'vulture.com/',
                                   'washingtonexaminer.com/', 'washingtonpost.com/', 'washingtontimes.com/',
                                   'weather2travel.com/', 'weeklystandard.com/', 'wegotthiscovered.com/',
                                   'westernjournal.com/', 'whatculture.com/', 'whosampled.com/', 'whoswhoinamerica.com/',
                                   'wikia.com/', 'wikia.org/', 'wikicities.com/', 'wikidata.org/', 'wikileaks.org/',
                                   'wikinews.org/', 'wikipedia.org/', 'wired.co.uk/', 'wired.com/', 'wnd.com/',
                                   'wordpress.com/', 'worldnetdaily.com/', 'worldometers.info/', 'wsj.com/', 'wsws.org/',
                                   'www.insider.com/', 'xbiz.com/', 'xinhuanet.com/', 'youtube.com/', 'zdnet.com/',
                                   'zerohedge.com/', 'zoominfo.com/'
                                  ); 
                                   // Just a list of ones that are obvious.  Add ones that time-out as we find them
                                   // bbm.ca is short enough that we add /bbm.ca/ and .bbm.ca/ since we don't want to grab too many sites

const NON_JOURNAL_DOIS = array('10.5531/db.vz.0001'); // lowercase exact matches
const NON_JOURNALS = array('Amphibian Species of the World', 'an Online Reference', 'An Online Reference'); // Case-sensitive sub-string
const ARE_MAGAZINES = array('the new yorker', 'the new republic', 'new republic', 'expedition magazine', 'wired', 'wired uk',
                           'computer gaming world', 'edge', 'edge (magazine)', 'game informer', 'pc gamer uk',
                           'wired (magazine)', 'time', 'life', 'time (magazine)', 'life (magazine)', 'billboard',
                           'billboard (magazine)', 'rolling stone', 'mcv/develop', 'vanity fair'
                           ); // lowercase axact matches
const ARE_MANY_THINGS = array('pc gamer', 'gamestar', 'rock paper shotgun', 'mcv', 'rock, paper, shotgun'); // lowercase axact matches.  These are things that are both websites and newspapers
const ARE_NEWSPAPERS = array('the economist', 'la times', 'toronto sun', 'washington post'); // lowercase axact matches
const NO_PUBLISHER_NEEDED = array('los angeles times', 'new york times magazine', 'the new york times',
                                   'new york times', 'huffington post', 'the daily telegraph', 'forbes.com',
                                   'forbes magazine'); // lowercase axact matches

const ENCYCLOPEDIA_WEB = array('plato.stanford.edu', 'britannica.com');

const GOOD_10_1093_DOIS = array( // March 2019 list
          'abbs', 'abm', 'abt', 'acn', 'adaptation', 'advances', 'ae', 'aepp', 'aesa', 'af', 'afraf',
          'ageing', 'ahr', 'ajae', 'ajcl', 'ajcn', 'ajcp', 'aje', 'ajh', 'ajhp', 'ajj', 'ajlh',
          'alcalc', 'aler', 'alh', 'alrr', 'amt', 'analysis', 'annhyg', 'annonc', 'antitrust', 'aob',
          'aobpla', 'applij', 'arbitration', 'aristotelian', 'aristoteliansupp', 'asj', 'asjopenforum', 'astrogeo', 'auk', 'beheco',
          'bfg', 'bib', 'bioinformatics', 'biolinnean', 'biolreprod', 'biomedgerontology', 'biomet', 'biomethods', 'bioscience', 'biostatistics',
          'bjaesthetics', 'bjc', 'bjps', 'bjsw', 'bmb', 'botlinnean', 'brain', 'btcint', 'bybil', 'camqtly',
          'carcin', 'cardiovascres', 'cb', 'ccc', 'cdj', 'cdn', 'ce', 'cercor', 'cesifo', 'chemse',
          'chinesejil', 'chromsci', 'cid', 'cjcl', 'cje', 'cjip', 'cjres', 'ckj', 'clp', 'cmlj',
          'comjnl', 'comnet', 'condor', 'conphys', 'cpe', 'criticalvalues', 'crj', 'crohnscolitis360', 'cs', 'ct',
          'cww', 'cybersecurity', 'cz', 'database', 'dh', 'dnaresearch', 'dote', 'dsh', 'ecco-jcc', 'ecco-jccs',
          'economicpolicy', 'ectj', 'edrv', 'ee', 'eep', 'ehjcimaging', 'ehjcr', 'ehjcvp', 'ehjqcco', 'ehr',
          'eic', 'ej', 'ejcts', 'ejil', 'ejo', 'eltj', 'em', 'emph', 'endo', 'english',
          'envhis', 'envihistrevi', 'epirev', 'erae', 'ereh', 'eshremonographs', 'esr', 'eurheartj', 'eurheartjsupp', 'europace',
          'eurpub', 'fampra', 'femsec', 'femsle', 'femspd', 'femsre', 'femsyr', 'fh', 'fmls', 'foreconshist',
          'forestry', 'forestscience', 'fpa', 'fqs', 'fs', 'fsb', 'gastro', 'gbe', 'geronj', 'gerontologist',
          'gh', 'gigascience', 'gji', 'globalsummitry', 'glycob', 'gsmnras', 'hcr', 'heapol', 'heapro', 'her',
          'hgs', 'hmg', 'hrlr', 'hropen', 'hsw', 'humrep', 'humupd', 'hwj', 'ia', 'ib',
          'ibdjournal', 'icb', 'icc', 'icesjms', 'icon', 'icsidreview', 'icvts', 'idpl', 'ije', 'ijl',
          'ijlct', 'ijlit', 'ijnp', 'ijpor', 'ijrl', 'ijtj', 'ilarjournal', 'ilj', 'imaiai', 'imajna',
          'imaman', 'imamat', 'imamci', 'imammb', 'imatrm', 'imrn', 'imrp', 'imrs', 'innovateage', 'insilicoplants',
          'integrablesystems', 'inthealth', 'intimm', 'intqhc', 'iob', 'ips', 'irap', 'isd', 'isle', 'isp',
          'isq', 'isr', 'itnow', 'iwc', 'jaar', 'jac', 'jae', 'jah', 'jamia', 'jamiaopen',
          'japr', 'jas', 'jat', 'jb', 'jbcr', 'jbi', 'jcag', 'jcb', 'jcem', 'jcle',
          'jcmc', 'jcr', 'jcs', 'jcsl', 'jdh', 'jdsde', 'jeclap', 'jee', 'jeea', 'jel',
          'jes', 'jfec', 'jfr', 'jge', 'jhc', 'jhered', 'jhmas', 'jhps', 'jhrp', 'jhs',
          'jicj', 'jid', 'jids', 'jiel', 'jigpal', 'jinsectscience', 'jiplp', 'jipm', 'jis', 'jjco',
          'jla', 'jlb', 'jleo', 'jmammal', 'jmcb', 'jme', 'jmicro', 'jmp', 'jmt', 'jn',
          'jnci', 'jncics', 'jncimono', 'jnen', 'joc', 'joeg', 'jof', 'jogss', 'jole', 'jos',
          'jpart', 'jpe', 'jpepsy', 'jpids', 'jpo', 'jpubhealth', 'jrls', 'jrr', 'jrs', 'jscr',
          'jsh', 'jss', 'jssam', 'jtm', 'jts', 'jue', 'jvc', 'jwelb', 'jxb', 'labmed',
          'lawfam', 'leobaeck', 'library', 'litimag', 'litthe', 'logcom', 'lpr', 'lril', 'maghis', 'mbe',
          'medlaw', 'melus', 'mend', 'migration', 'milmed', 'mind', 'mj', 'ml', 'mmy', 'mnras',
          'mnrasl', 'molehr', 'mollus', 'monist', 'mq', 'mspecies', 'mtp', 'mts', 'musictherapy', 'mutage',
          'nar', 'nass', 'nc', 'ndt', 'neuro-oncology', 'neurosurgery', 'njaf', 'nop', 'nq', 'nsr',
          'ntr', 'nutritionreviews', 'oaj', 'occmed', 'oep', 'ofid', 'ohr', 'ojlr', 'ojls', 'omcr',
          'ons', 'oq', 'oxrep', 'pa', 'painmedicine', 'pasj', 'past', 'pch', 'pcm', 'pcp',
          'peds', 'petrology', 'phe', 'philmat', 'plankt', 'policing', 'poq', 'ppar', 'ppmg', 'ppr',
          'pq', 'proceedingslinnean', 'ps', 'psychsocgerontology', 'ptep', 'ptj', 'ptp', 'ptps', 'publius', 'qje',
          'qjmam', 'qjmath', 'qjmed', 'raps', 'rb', 'rcfs', 'reep', 'res', 'restud', 'rev',
          'rfs', 'rheumap', 'rheumatology', 'rof', 'rpc', 'rpd', 'rsq', 'scan', 'schizophreniabulletin', 'screen',
          'ser', 'sf', 'shm', 'sjaf', 'sleep', 'slr', 'socpro', 'socrel', 'sp', 'spp',
          'sq', 'ssjj', 'sw', 'swr', 'swra', 'synbio', 'sysbio', 'tandt', 'tas', 'tbm',
          'tcbh', 'teamat', 'toxsci', 'transactionslinnean', 'transactionslinneanbot', 'transactionslinneanzoo', 'treephys', 'tropej', 'trstmh', 'tse',
          'ulr', 've', 'wber', 'wbro', 'whq', 'wjaf', 'workar', 'yel', 'yielaw', 'ywcct',
          'ywes', 'zoolinnean');


// List of things to not print links to, since they occur all the time
const AVOIDED_LINKS = array('', 'Digital_object_identifier', 'JSTOR', 'Website', 'International_Standard_Book_Number',
                            'Library_of_Congress_Control_Number', 'Handle_System', 'PubMed_Central', 'PubMed',
                            'PubMed_Identifier', 'Bibcode', 'International_Standard_Serial_Number', 'bioRxiv',
                            'CiteSeerX', 'Zentralblatt_MATH', 'Jahrbuch_über_die_Fortschritte_der_Mathematik',
                            'Mathematical_Reviews', 'Office_of_Scientific_and_Technical_Information',
                            'Request_for_Comments', 'Social_Science_Research_Network', 'Zentralblatt_MATH',
                            'Open_Library', 'ArXiv', 'OCLC', 'Cf.');

// Lower case, and periods converted to spaces
const JOURNAL_IS_BOOK_SERIES = array('methods of molecular biology' , 'methods mol biol',
                                     'methods of molecular biology (clifton, n j )',
                                     'methods in molecular biology',
                                     'methods in molecular biology (clifton, n j )',
                                     'advances in pharmacology (san diego, calif )',
                                     'advances in pharmacology', 'inorganic syntheses',
                                     'advances in enzymology and related areas of molecular biology',
                                     'studies in bilingualism', 'antibiotics and chemotherapy');
