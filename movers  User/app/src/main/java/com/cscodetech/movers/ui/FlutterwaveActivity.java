package com.cscodetech.movers.ui;

import android.os.Bundle;
import android.webkit.WebView;

import com.cscodetech.movers.R;
import com.cscodetech.movers.model.UserLogin;
import com.cscodetech.movers.retrofit.APIClient;
import com.cscodetech.movers.utils.WebViewClientImpl;

import java.io.UnsupportedEncodingException;
import java.net.URLEncoder;


public class FlutterwaveActivity extends BaseActivity {
    double amount = 0;

    UserLogin user;
    WebView webView;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_flutterwave);
        user = sessionmanager.getUserDetails();
        webView = findViewById(R.id.webview);
        amount = getIntent().getDoubleExtra("amount", 0);
        String postData = null;
        try {
            postData = "&amt=" + URLEncoder.encode(String.valueOf(amount), "UTF-8");
        } catch (UnsupportedEncodingException e) {
            e.printStackTrace();
        }
        String url = APIClient.BASE_URL + "/flutterwave/index.php?" + postData;
        webView.getSettings().setJavaScriptEnabled(true);
        WebViewClientImpl webViewClient = new WebViewClientImpl(FlutterwaveActivity.this,webView);
        webView.setWebViewClient(webViewClient);
        webView.loadUrl(url);


    }


}