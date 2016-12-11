/*
 * Copyright 2016, The Plexian Authors
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License in the LICENSE.md file.
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
   * @param applicationID Your application's ID
   * @param applicationToken Your application's private token
   * @param phpAPIURL The url for the PHP API file. NOTE: Must implement Plexian
   *     Distribution Protocol 0.1 in order to function properly.
   * @param async A boolean: true if use async connections. Default is false.
   */
  constructor(applicationID, applicationToken, phpAPIURL, async){
		this.applicationID = applicationID;
		this.applicationToken = applicationToken;
		this.apiURL = phpAPIURL;
		this.useAsync = (async !== undefined ? async : true);
  }
	
	/**
	 * Check if we use asnychronous requests or not
	 *
	 * @return True if so, false if not.
	 */
  useAsync(){
    return this.useAsync;
  }
	
	/**
	 * Set if we should use asnychronous requests or not
	 *
	 * @param val The boolean value to set useAsync to.
	 */
  setUseAsync(val){
    if(val){
      this.useAsync = true;
    }else{
      this.useAsync = false;
    }
  }
	
  /**
   * Initialize the connection to the far-API
   *
   * @param productKey The product key to validate.
   * @param callback The function to call with the response (only used with asynchronous requests)
   * @return The response if using synchronous requests or null if using asynchronous
   */
  validateProductKey(productKey, callback){
    let stateURL = this.apiURL + "?state=" + JSON.stringify(
      {
        request: "validate_product_key",
        input: {
          "key": productKey,
          application: this.applicationID
        }
      }
    );
    
    // If there is no product key given then simply
    if(!productKey){
      if(this.useAsync()){
        callback({result: "error", error: {id: "0", message: "Missing state parameter"}});
        return;
      }else{
        return {result: "error", error: {id: "0", message: "Missing state parameter"}};
      }
    }
    
    let request = new XMLHttpRequest();
    var text;
    
    // If we are using asynchronous requests we need to use callbacks.
    if(this.useAsync()){
      // When the request returns we need to read the state
      request.onreadystatechange = function(){
        // If the request is fully complete
        if(request.readyState == 4 && request.status == 200){
          // Parse the string returned (see api.php for more information) into a Javascript object
          text = JSON.parse(request.responseText);
          
          // If there was some formatting error in the response return a MisformattedResult error.
          if(!text){
            callback({result: "error", error: {id: "6", message: "Misformatted result"}});
            return;
          }
          // Otherwise return the response.
          else{
            callback(text);
            return;
          }
        }
      };
    }
    // If we aren't then we need to just return the response.
    else{
      request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
          text = JSON.parse(request.responseText);
          
          if(!text){
            text = {result: "error", error: {id: "6", message: "Misformatted result"}};
          }
        }
      };
    }
    
    // Open the connection then send it.
    request.open("GET", stateURL, this.useAsync());
    request.send();
    
    return text;
  }
  
  /**
   * Adds a product key to the database for your application
   *
   * @param productKey The product key to add (must be unique to your application so use the UUID library or equivelent)
   * @param callback The function to call with the response (only used with asynchronous requests)
   * @return The response if using synchronous requests or null if using asynchronous
   */
  addProductKey(productKey, callback){
    let stateURL = this.apiURL + "?state=" + JSON.stringify(
      {
        request: "add_application_key",
        input: {
          application: this.applicationID,
          application_authorization: this.applicationToken,
          new_key: productKey
        }
      }
    );
  
    if(!productKey){
      if(this.useAsync()){
        callback({result: "error", error: {id: "0", message: "Missing state parameter"}});
        return;
      }else{
        return {result: "error", error: {id: "0", message: "Missing state parameter"}};
      }
    }
    
    let request = new XMLHttpRequest();
    var text;
    
    if(this.useAsync()){
      request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
          text = JSON.parse(request.responseText);
          
          if(!text){
            callback({result: "error", error: {id: "6", message: "Misformatted result"}});
            return;
          }else{
            callback(text);
            return;
          }
        }
      };
    }else{
      request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
          text = JSON.parse(request.responseText);
          
          if(!text){
            text = {result: "error", error: {id: "6", message: "Misformatted result"}};
          }
        }
      };
    }
    
    request.open("GET", stateURL, this.useAsync());
    request.send();
    
    return text;
  }
  
  deleteProductKey(productKey, callback){
    let stateURL = this.apiURL + "?state=" + JSON.stringify(
      {
        request: "remove_product_key",
        input: {
          application: this.applicationID,
          application_authorization: this.applicationToken,
          key: productKey
        }
      }
    );
    
    if(!productKey){
      if(this.useAsync()){
        callback({result: "error", error: {id: "0", message: "Missing state parameter"}});
        return;
      }else{
        return {result: "error", error: {id: "0", message: "Missing state parameter"}};
      }
    }
    
    let request = new XMLHttpRequest();
    var text;
    
    if(this.useAsync()){
      request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
          text = JSON.parse(request.responseText);
          
          if(!text){
            callback({result: "error", error: {id: "6", message: "Misformatted result"}});
            return;
          }else{
            callback(text);
          }
        }
      };
    }else{
      request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
          text = JSON.parse(request.responseText);
          
          if(!text){
            text = {result: "error", error: {id: "6", message: "Misformatted result"}};
          }
        }
      };
    }
    
    request.open("GET", stateURL, this.useAsync());
    request.send();
    return text;
  }
  
  disableProductKey(productKey, callback){
  }
  
  enableProductKey(productKey, callback){
    // TODO: implement here and in server.
  }
}