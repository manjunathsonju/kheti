<?php
class ControllerKhetiComponents extends Controller {
	public function index() {
        $sql ="SELECT c.category_id,cd.name,c.image,cd.meta_title FROM oc_category c JOIN oc_category_description cd ON (cd.category_id=c.category_id) JOIN oc_category_to_store cs ON (cs.category_id=c.category_id) WHERE c.category_id IN (172,137,171,139,215,212,225,213,218, 238,216,220,221,227,230,134,247) AND cd.language_id=1 AND cs.store_id=1";
        $cat=$this->db->query($sql);
        
        foreach ($cat->rows as $result){
            if(strlen($result['image'])!=0){
                $image='https://khetifood.com/image/'.$result['image'];
            }else{
                $image="https://khetifood.com/image/no_image.png";
            }
            $Categories[]=array(
                'category_id'=>$result['category_id'],
                'name'=>$result['name'],
                'image'=>$image,
                'meta_title'=>$result['meta_title']
            );
        }
        return $Categories;
    }
    public function categories() {
        $sql ="SELECT c.category_id,cd.name,c.image,cd.meta_title FROM oc_category c JOIN oc_category_description cd ON (cd.category_id=c.category_id) JOIN oc_category_to_store cs ON (cs.category_id=c.category_id) WHERE c.category_id IN (172,137,171,139,215,212,225,213,218, 238,216,220,221,227,230,134,247) AND cd.language_id=1 AND cs.store_id=1";
        $cat=$this->db->query($sql);
        
        foreach ($cat->rows as $result){
            if(strlen($result['image'])!=0){
                $image='https://khetifood.com/image/'.$result['image'];
            }else{
                $image="https://khetifood.com/image/no_image.png";
            }
            $Categories[]=array(
                'category_id'=>$result['category_id'],
                'name'=>$result['name'],
                'image'=>$image,
                'meta_title'=>$result['meta_title']
            );
        }
        return $Categories;
    }
}