<?php
namespace Database\Seeders;
use App\Models\{MediaListing,Creator,Service,Project,Article,Training};
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
class DatabaseSeeder extends Seeder {
 public function run():void{
  // All seeded media, creators and portfolio items are explicitly illustrative. Not verified inventory or RA Media client history.
  $media=[
   ['Kuala Lumpur LED Billboard','Outdoor','listing-outdoor','Klang Valley','Urban commuters','High-impact digital roadside creative format.','on_request'],
   ['National TV Brand Feature','TV & Broadcast','listing-tv','Nationwide','Mass-market households','Brand storytelling and broadcast feature opportunities.','on_request'],
   ['Drive-Time Radio Placement','Radio','listing-radio','Nationwide','Daily commuters','Radio campaigns and audience-focused slots.','on_request'],
   ['Retail Mall Screen Network','Indoor','listing-indoor','Klang Valley','Retail shoppers','Digital screen media across shopping environments.','on_request'],
   ['Niche Creator Campaign','Creators','listing-creators','Nationwide','Targeted interest communities','Creator-led storytelling matched to audience interests.','on_request'],
   ['Digital Video Advertising','Digital','listing-digital','Nationwide','Digital-first consumers','Cross-platform video and digital campaign placements.','on_request'],
   ['Print Feature & Advertorial','Print','print','Nationwide','Editorial readers','Editorial-style and standard print advertising options.','on_request'],
   ['Podcast Brand Integration','Podcast','radio','Nationwide','Focused interest groups','Host-led conversations and sponsorship opportunities.','on_request'],
   ['Building Wall Activation','Outdoor','custom-media','Klang Valley','Local high-footfall audience','Custom-format wall media subject to site rights and approvals.','on_request'],
  ];
  foreach($media as $i=>$a){MediaListing::updateOrCreate(['slug'=>Str::slug($a[0])],['title'=>$a[0],'type'=>$a[1],'channel'=>$a[1],'location'=>$a[3],'audience'=>$a[4],'summary'=>$a[5],'description'=>'Concept example for platform demonstration. Actual suppliers, availability and rates require commercial verification.','image'=>'assets/img/'.$a[2].'.webp','pricing_mode'=>$a[6],'availability_status'=>'on_request','is_featured'=>$i<6,'is_published'=>true]);}
  foreach([
   ['Media Planning & Buying','Media Strategy','Strategy and placements across TV, radio, outdoor, print and digital.','tv'],
   ['Branding & Creative Studio','Creative','Identity systems, graphic design, campaign concepts, visual and audio assets.','digital'],
   ['AI Video & Image Production','AI & Technology','Concept-to-content workflows for image, film, music and creative production.','creator-feature'],
   ['Websites & Conversion Systems','Technology','Landing pages, websites, CRM, campaign tracking and workflow automation.','custom-media'],
   ['Creator & Influencer Campaigns','Creator Network','Niche creator matching, briefing, creative production and measurement.','creators'],
   ['Media Asset Monetisation','Custom Media','Turn appropriate premises and physical spaces into sellable advertising assets.','indoor'],
   ['AI Seminars & Corporate Training','Academy','Practical AI, content, productivity and transformation workshops.','training'],
   ['Campaign Analytics & Automation','Intelligence','Attribution, dashboards, lead handling and measurable marketing workflows.','digital'],
  ] as $i=>$a){Service::updateOrCreate(['slug'=>Str::slug($a[0])],['title'=>$a[0],'category'=>$a[1],'summary'=>$a[2],'image'=>'assets/img/'.$a[3].'.webp','is_featured'=>true,'sort_order'=>$i,'is_published'=>true]);}
  foreach([
   ['Travel & Local Discovery','Travel','TikTok','creators'],['Food & Lifestyle','Food','Instagram','creator-feature'],['Technology & Productivity','Technology','YouTube','digital'],['Home & Family','Family','TikTok','indoor']
  ] as $i=>$c){Creator::updateOrCreate(['slug'=>'demo-creator-'.($i+1)],['name'=>'Demo Creator '.($i+1),'handle'=>'@demo_creator_'.($i+1),'platform'=>$c[2],'niche'=>$c[1],'location'=>'Malaysia','bio'=>'Illustrative creator profile only. Not a real creator or verified partnership.','image'=>'assets/img/'.$c[3].'.webp','pricing_mode'=>'on_request','status'=>'active','is_featured'=>true]);}
  foreach([
   ['Integrated City Awareness Concept','Outdoor','A conceptual multi-touchpoint city awareness campaign.','outdoor'],
   ['Creator-Led Product Launch Concept','Creators','A demonstration of targeted creator collaboration and conversion journeys.','creators'],
   ['Smart Media Conversion Concept','Digital','An example of connecting broadcast attention to a measurable enquiry funnel.','digital'],
  ] as $i=>$p){Project::updateOrCreate(['slug'=>Str::slug($p[0])],['title'=>$p[0],'category'=>$p[1],'summary'=>$p[2],'image'=>'assets/img/'.$p[3].'.webp','is_concept'=>true,'is_published'=>true,'sort_order'=>$i]);}
  foreach([
   ['How to Plan a Better Media Mix','Strategy','Start with the audience, outcome and measurement plan before choosing channels.','editorial-media-mix'],
   ['Why Niche Creators Matter','Creator Economy','The right creator is not always the one with the most followers.','editorial-creators'],
   ['From Awareness to Enquiries','Measurement','Link offline and online channels to appropriate conversion points.','editorial-measurement'],
  ] as $a){Article::updateOrCreate(['slug'=>Str::slug($a[0])],['title'=>$a[0],'category'=>$a[1],'excerpt'=>$a[2],'body'=>$a[2]."\n\nWork backwards from commercial outcomes. Define the audience and communications objective, design useful creative, select suitable distribution channels, and use a practical measurement plan. A campaign is a learning process, not a guarantee of sales.",'image'=>'assets/img/'.$a[3].'.webp','published_at'=>now(),'is_published'=>true]);}
  foreach([
   ['AI for Business & Marketing','AI','Practical AI workflows for content, research and business operations.'],
   ['Creative Video Production','Production','Plan, generate, edit and distribute creative video content.'],
   ['Digital Marketing & Conversion','Marketing','Design campaigns with measurable calls to action and customer journeys.'],
  ] as $t){Training::updateOrCreate(['slug'=>Str::slug($t[0])],['title'=>$t[0],'category'=>$t[1],'summary'=>$t[2],'format'=>'Corporate / Workshop','duration'=>'Customisable','image'=>'assets/img/training.webp','is_published'=>true]);}
 }
}
