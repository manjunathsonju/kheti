<?php
class ControllerBlogArticle extends Controller {
	public function index() {
        $data['article_id'] = $this->request->get['article_id'];
        $beegees=$this->db->query("SELECT b.view,b.simple_blog_article_id,b.image,b.date_added,b.date_modified,b.simple_blog_author_id,ba.name,bd.article_title,bd.description,bd.meta_description FROM oc_simple_blog_article b JOIN oc_simple_blog_article_description bd ON (bd.simple_blog_article_id=b.simple_blog_article_id) JOIN oc_simple_blog_author ba ON (ba.simple_blog_author_id=b.simple_blog_author_id) WHERE b.status=1
        AND bd.language_id=1 AND b.simple_blog_article_id='".$data['article_id']."'");
                 foreach ($beegees->rows as $result){
                    if($result['image']){
                        $image1='https://khetifood.com/image/'.$result['image'];
                    }else {
                        $image1='';
                    }
        
                    $blogs =array(
                    'id'=>$result['simple_blog_article_id'],
                    'view'=>$result['view'],
                    'image'=>$image1,
                    'date_added'=>date("l, F d Y", strtotime($result['date_added'])),
                    'date_modified'=>$result['date_modified'],
                    'author_id'=>$result['simple_blog_author_id'],
                    'name'=>$result['name'],
                    'article_title'=>$result['article_title'],
                    'description'=>html_entity_decode($result['description'],ENT_QUOTES, 'UTF-8')
                );
              }
            //   var_dump($beegees);

           

        if( $blogs['id']==NULL){
            var_dump('doesnot exist');
        }else{
            $add_desc=$this->db->query("SELECT * FROM `oc_simple_blog_article_description_additional` WHERE simple_blog_article_id='".$blogs['id']."'");
            foreach ($add_desc->rows as $result){
                $data['descriptions'][] =array(
                    'text'=>html_entity_decode($result['additional_description'], ENT_QUOTES, 'UTF-8')  
                  );

            }
            // var_dump($data['description']);
            
            $this->db->query("UPDATE `oc_simple_blog_article` SET view=view+1 WHERE simple_blog_article_id='".$blogs['id']."'");
            $data['blogs']= $blogs;
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');
            //latest
        $late=$this->db->query("SELECT b.view,b.simple_blog_article_id,b.image,b.date_added,b.date_modified,b.simple_blog_author_id,ba.name,bd.article_title,bd.description,bd.meta_description FROM oc_simple_blog_article b JOIN oc_simple_blog_article_description bd ON (bd.simple_blog_article_id=b.simple_blog_article_id) JOIN oc_simple_blog_author ba ON (ba.simple_blog_author_id=b.simple_blog_author_id) WHERE b.status=1
        AND bd.language_id=1 AND b.date_added=(SELECT MAX(date_added) FROM oc_simple_blog_article WHERE status=1)");
                 foreach ($late->rows as $result){
                    if($result['image']){
                        $image1='https://khetifood.com/image/'.$result['image'];
                    }else {
                        $image1='';
                    }
                    $latest =array(
                    'id'=>$result['simple_blog_article_id'],
                    'view'=>$result['view'],
                    'image'=> $image1,
                    'date_added'=>$result['date_added'],
                    'date_modified'=>$result['date_modified'],
                    'author_id'=>$result['simple_blog_author_id'],
                    'name'=>$result['name'],
                    'article_title'=>$result['article_title'],
                    'description'=>$result['description'],
                    'link'=>'index.php?route=blog/article&article_id='.$result['simple_blog_article_id']

                  );
              }
        $data['latest']= $latest;
        //latest end
         //top
         $top=$this->db->query("SELECT b.view,b.simple_blog_article_id,b.image,b.date_added,b.date_modified,b.simple_blog_author_id,ba.name,bd.article_title,bd.description,bd.meta_description FROM oc_simple_blog_article b JOIN oc_simple_blog_article_description bd ON (bd.simple_blog_article_id=b.simple_blog_article_id) JOIN oc_simple_blog_author ba ON (ba.simple_blog_author_id=b.simple_blog_author_id) WHERE b.status=1
         AND bd.language_id=1 AND b.view=(SELECT MAX(view) FROM oc_simple_blog_article WHERE status=1 LIMIT 1) LIMIT 1");
                  foreach ($top->rows as $result){
                    if($result['image']){
                        $image1='https://khetifood.com/image/'.$result['image'];
                    }else {
                        $image1='';
                    }
                     $tops =array(
                     'id'=>$result['simple_blog_article_id'],
                     'view'=>$result['view'],
                     'image'=>$image1,
                     'date_added'=>$result['date_added'],
                     'date_modified'=>$result['date_modified'],
                     'author_id'=>$result['simple_blog_author_id'],
                     'name'=>$result['name'],
                     'article_title'=>$result['article_title'],
                     'description'=>$result['description'],
                     'link'=>'index.php?route=blog/article&article_id='.$result['simple_blog_article_id']

                   );
               }
         $data['top']= $tops;
         //top end

            $this->response->setOutput($this->load->view('blog/article',$data));

        }
       
    }
}