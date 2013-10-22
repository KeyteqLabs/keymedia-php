guard 'phpunit', :command => './vendor/bin/phpunit', :cli => '--colors --bootstrap vendor/autoload.php', :tests_path => 'tests' do
  watch(%r{^tests/.+Test\.php$})
  watch(%r{^src/(.+)\.php$}) { |m| "tests/#{m[1]}Test.php" }
end
