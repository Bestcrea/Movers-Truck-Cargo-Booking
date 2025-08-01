package com.cscodetech.movers.model.postload;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class MyLoadData{

	@SerializedName("LoadDetails")
	@Expose
	private LoadDetails loadDetails;
	@SerializedName("ResponseCode")
	@Expose
	private String responseCode;
	@SerializedName("Result")
	@Expose
	private String result;
	@SerializedName("ResponseMsg")
	@Expose
	private String responseMsg;

	public LoadDetails getLoadDetails() {
		return loadDetails;
	}

	public void setLoadDetails(LoadDetails loadDetails) {
		this.loadDetails = loadDetails;
	}

	public String getResponseCode() {
		return responseCode;
	}

	public void setResponseCode(String responseCode) {
		this.responseCode = responseCode;
	}

	public String getResult() {
		return result;
	}

	public void setResult(String result) {
		this.result = result;
	}

	public String getResponseMsg() {
		return responseMsg;
	}

	public void setResponseMsg(String responseMsg) {
		this.responseMsg = responseMsg;
	}

}