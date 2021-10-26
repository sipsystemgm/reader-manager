2.2.2

* change [composer.json] added php version

2.2.1

* feature [AbstractFactoryInterface, ReaderManagerInterface] was added parameters to test subdomain

2.1.1

* feature [ReaderStorageInterface] seperated to ReaderFilterStorageInterface and ReadCacheInterface
* change [AbstractStorage] constructor was moved to FileStorage
* change [FileStorage] was implemented functions ReaderFilterStorageInterface

1.5.2

* change [ReaderManager::run] was added loop index end pass it to user function

1.5.1

* change [ReaderManager] user function was moved inside loop in run method and renamed

1.4.3

* change [AbstractStorage] saving data in a storage
* change [ReaderStorageInterface] created public function getUrl to get an url Data by an url

1.4.2

* change [ReaderManager] created function returns a scheme and domain from url

1.4.1

* remove [ReaderManager] first argument $domain in function ReaderManagerInterface::run
* change [ReaderManagerTest] changed tests

1.3.1

* feature [README] new file
* feature [CHANGELOG] new file
* change [ReaderManager] getting factory was moved in a separate function 

1.2.1

* deleted unused library
* change [FileStorage]  was moved to the parent abstract class
* change run method

1.1.1

* feature It was added ReaderManager and tests