<?php
interface RecipeStorage{
  public function read($id);
  public function readAll();
  public function create(Recipe $recipe);
}
?>
