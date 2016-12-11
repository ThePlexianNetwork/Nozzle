/*
 * Copyright 2016, The Plexian Authors
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the LICENSE.md file for the specific language governing permissions and
 * limitations under the License.
 */
 
/**
 *
 * The class for managing API connections with different servers. This class
 * allows for connections that fall under Plexian Distribution Protocol v0.1.
 *
 * @author Walt Pach
 * @since 0.0.1
 */
class APIConnectionManager {
	/**
	 * Create a new instance of APIConnectionManager
	 *
	 * @param phpAPIURL The url for the PHP API file. NOTE: Must implement Plexian
	 *     Distribution Protocol 0.1 in order to function properly.
	 * @param async A boolean: true if use async connections. Default is false.
	 */
	constructor(phpAPIURL, async){
		this.apiURL = phpAPIURL;
		this.useAsync = (async !== undefined ? async : true);
	}
	
	/**
	 *
	 */
  initializeConnection(){
  	if(!this.apiURL){
  		Error("Cannot initialize connection with null API.");
  	}
  }
}