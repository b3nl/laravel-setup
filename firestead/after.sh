#!/bin/sh

cd /vagrant/;

# init resource dependencies, globally for vagrant/windows problems.
npm install --global bower gulp@^3.8.8 laravel-elixir@^2.0.0;
bower install --allow-root --quiet;
