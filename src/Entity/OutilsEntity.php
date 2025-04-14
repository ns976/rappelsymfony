<?php

    namespace App\Entity;

    trait OutilsEntity
    {


        public function setSlug(string $slug): self
        {
            $this->slug = strtolower($slug);

            return $this;
        }

        public function setName(string $name): self
        {
            $this->name = ucfirst( strtolower($name));

            return $this;
        }
    }