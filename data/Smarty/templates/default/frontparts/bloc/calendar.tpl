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
<!--▼カレンダーここから-->
      <section class="calender_box">
        <h2>営業部カレンダー</h2>
        <div class="box">
          <!--{section name=num loop=$arrCalendar}-->
          <!--{assign var=arrCal value=`$arrCalendar[num]`}-->
          <!--{section name=cnt loop=$arrCal}-->
          <!--{if $smarty.section.cnt.first}-->
          <table>
            <caption class="month">
            <!--{$arrCal[cnt].year}-->年<!--{$arrCal[cnt].month}-->月の定休日
            </caption>
            <thead>
              <tr>
                <th><span class="sunday">日</span></th>
                <th><span>月</span></th>
                <th><span>火</span></th>
                <th><span>水</span></th>
                <th><span>木</span></th>
                <th><span>金</span></th>
                <th><span class="saturday">土</span></th>
              </tr>
            </thead>
          <!--{/if}-->
          <!--{if $arrCal[cnt].first}-->
          <tr>
          <!--{/if}-->
          <!--{if !$arrCal[cnt].in_month}-->
          <td><span>&nbsp;</span></td>
          <!--{elseif $arrCal[cnt].holiday}-->
          <td class="off"><!--{$arrCal[cnt].day}--></td>
          <!--{else}-->
          <td><span><!--{$arrCal[cnt].day}--></span></td>
          <!--{/if}-->
          <!--{if $arrCal[cnt].last}-->
          </tr>
          <!--{/if}-->
          <!--{/section}-->
          <!--{if $smarty.section.cnt.last}-->
          </table>
          <!--{/if}-->
          <!--{/section}-->
          
          <h4><img src="<!--{$TPL_URLPATH}-->img/page/bloc/calender_box/icon_01.png" width="21" height="27" alt="休業日" /> 休業日</h4>
          <p class="information">
          営業時間<br />
  	＜平　日＞AM 10:00 ～ PM 7:00<br />
  	＜土曜日＞AM 10:00 ～ PM 5:00<br />
  	定休日<br />
  	日曜日・祝際日・毎月第２・第４土曜日
          </p>
        </div>
      </section>
<!--▲カレンダーここまで-->
