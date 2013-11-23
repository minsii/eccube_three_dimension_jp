<!--{*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2007 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *}-->

      <!-- ▼新着情報--> 
      <div class="pure-g">
          <section class="news_box pure-u-1-2">
               <h2><img src="<!--{$TPL_URLPATH}-->img/page/bloc/news_box/title.png" width="359" height="30" alt="新着情報" /></h2>
               <ul>
<!--{section name=data loop=$arrNews}-->
  <!--{assign var=news_no value="`$smarty.section.data.iteration`"}-->
  <li <!--{if $news_no % 2 == 0}--> class="row" <!--{/if}-->>
    <span><!--{$arrNews[data].news_date_disp|date_format:"%Y.%m.%d"}--></span> 
      <!--{if $arrNews[data].news_url}-->
      <a href="<!--{$arrNews[data].news_url}-->"
        <!--{if $arrNews[data].link_method eq "2"}-->
        target="_blank"
        <!--{/if}-->>
      <!--{/if}-->
      <!--{$arrNews[data].news_title|escape|nl2br}-->
        <!--{if $arrNews[data].news_url}-->
      </a>
      <!--{/if}-->
  </li>
<!--{/section}-->
          </ul>
          </section>
          <section class="campaign_box pure-u-1-2">
               <h2><img src="<!--{$TPL_URLPATH}-->img/page/bloc/campaign_box/title.png" width="359" height="30" alt="開催中のキャンペーン" /></h2>
               <ul>
                   <li><span><img src="<!--{$TPL_URLPATH}-->img/page/bloc/campaign_box/icon_sale.png" width="49" height="16" alt="SALE" /></span> ああああああああああ</li>
                   <li class="row"><span><img src="<!--{$TPL_URLPATH}-->img/page/bloc/campaign_box/icon_tokushu.png" width="49" height="16" alt="特集" /></span> ああああああああああ</li>
                   <li><span><img src="<!--{$TPL_URLPATH}-->img/page/bloc/campaign_box/icon_zaiko.png" width="49" height="16" alt="在庫市" /></span> ああああああああああ</li>
                   <li class="row"><span><img src="<!--{$TPL_URLPATH}-->img/page/bloc/campaign_box/icon_sale.png" width="49" height="16" alt="SALE" /></span> ああああああああああ</li>
               </ul>
          </section>
      </div>
      <!-- ▲新着情報 --> 
