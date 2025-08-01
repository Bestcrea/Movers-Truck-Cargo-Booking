package com.cscodetech.movers.fragment;

import static com.cscodetech.movers.utils.FileUtils.createPartFromString;
import static com.cscodetech.movers.utils.FileUtils.prepareFilePart;
import static com.cscodetech.movers.utils.SessionManager.intro;
import static com.cscodetech.movers.utils.SessionManager.language;
import static com.cscodetech.movers.utils.SessionManager.login;
import static com.cscodetech.movers.utils.SessionManager.wallet;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.provider.MediaStore;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import androidx.activity.result.ActivityResultLauncher;
import androidx.activity.result.contract.ActivityResultContracts;
import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.DefaultItemAnimator;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.bumptech.glide.Glide;
import com.cscodetech.movers.R;
import com.cscodetech.movers.model.Help;
import com.cscodetech.movers.model.Login;
import com.cscodetech.movers.model.Pages;
import com.cscodetech.movers.model.UserLogin;
import com.cscodetech.movers.retrofit.APIClient;
import com.cscodetech.movers.retrofit.GetResult;
import com.cscodetech.movers.ui.FaqActivity;
import com.cscodetech.movers.ui.HelpDetailsActivity;
import com.cscodetech.movers.ui.HomeActivity;
import com.cscodetech.movers.ui.ReferActivity;
import com.cscodetech.movers.ui.ReviewActivity;
import com.cscodetech.movers.ui.WalletActivity;
import com.cscodetech.movers.utils.CustPrograssbar;
import com.cscodetech.movers.utils.SessionManager;
import com.google.android.material.bottomsheet.BottomSheetDialog;
import com.google.android.material.imageview.ShapeableImageView;
import com.google.android.material.textfield.TextInputEditText;
import com.google.gson.Gson;
import com.google.gson.JsonObject;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.util.ArrayList;
import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;
import okhttp3.MediaType;
import okhttp3.MultipartBody;
import okhttp3.RequestBody;
import retrofit2.Call;


public class ProfileFragment extends Fragment implements GetResult.MyListener {

    @BindView(R.id.imgs)
    public ShapeableImageView imgs;
    @BindView(R.id.txt_name)
    public TextView txtName;
    @BindView(R.id.txt_rates)
    public TextView txtRates;
    @BindView(R.id.txt_mobile)
    public TextView txtMobile;
    @BindView(R.id.img_edit)
    public ImageView imgEdit;

    @BindView(R.id.lvl_logout)
    public LinearLayout lvlLogout;

    @BindView(R.id.recycler_page)
    public RecyclerView recyclerPage;
    SessionManager sessionManager;
    UserLogin userLogin;
    CustPrograssbar custPrograssbar;

    String selectImage;
    String applicationjson = "application/json";


    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        View view = inflater.inflate(R.layout.fragment_profile, container, false);
        ButterKnife.bind(this, view);

        sessionManager = new SessionManager(getActivity());
        custPrograssbar = new CustPrograssbar();
        userLogin = sessionManager.getUserDetails();
        txtName.setText("" + userLogin.getName());
        txtRates.setText("" + userLogin.getEmail());
        txtMobile.setText("" + userLogin.getCcode() + " " + userLogin.getMobile());
        Glide.with(getActivity())
                .load(APIClient.BASE_URL + "/" + userLogin.getProPic())
                .thumbnail(Glide.with(this).load(R.drawable.tprofile))
                .into(imgs);

        LinearLayoutManager mLayoutManager2 = new LinearLayoutManager(getActivity());
        mLayoutManager2.setOrientation(LinearLayoutManager.VERTICAL);
        recyclerPage.setLayoutManager(mLayoutManager2);
        recyclerPage.setItemAnimator(new DefaultItemAnimator());

        getPagelist();
        return view;
    }

    private void getPagelist() {
        custPrograssbar.prograssCreate(getActivity());
        JSONObject jsonObject = new JSONObject();
        try {
            jsonObject.put("uid", userLogin.getId());
        } catch (Exception e) {
            e.printStackTrace();

        }
        RequestBody bodyRequest = RequestBody.create(jsonObject.toString(), MediaType.parse(applicationjson));
        Call<JsonObject> call = APIClient.getInterface().pagelist(bodyRequest);
        GetResult getResult = new GetResult(getActivity());
        getResult.setMyListener(this);
        getResult.callForLogin(call, "profile");

    }

    @OnClick({R.id.img_edit, R.id.lvl_wallet, R.id.lvl_refer, R.id.lvl_logout, R.id.imgs, R.id.lvl_mult_lang, R.id.lvl_review, R.id.lvl_faq})
    public void onBindClick(View view) {
        switch (view.getId()) {
            case R.id.img_edit:
                editProfile(getActivity());
                break;
            case R.id.imgs:
                Intent intent = new Intent(Intent.ACTION_PICK, MediaStore.Images.Media.EXTERNAL_CONTENT_URI);
                launcher.launch(intent);

                break;
            case R.id.lvl_wallet:
                startActivity(new Intent(getActivity(), WalletActivity.class));
                break;
            case R.id.lvl_refer:
                startActivity(new Intent(getActivity(), ReferActivity.class));
                break;

            case R.id.lvl_logout:
                sessionManager.logoutUser();
                getActivity().finish();
                break;
            case R.id.lvl_mult_lang:
                bottonLanguageList();
                break;
            case R.id.lvl_review:
                startActivity(new Intent(getActivity(), ReviewActivity.class));
                break;
            case R.id.lvl_faq:
                startActivity(new Intent(getActivity(), FaqActivity.class));
                break;
            default:
                break;

        }
    }

    public void editProfile(Context context) {
        Activity activity = (Activity) context;
        BottomSheetDialog mBottomSheetDialog = new BottomSheetDialog(activity);
        View rootView = activity.getLayoutInflater().inflate(R.layout.editprofile_layout, null);
        mBottomSheetDialog.setContentView(rootView);
        TextView mobile = rootView.findViewById(R.id.edit_mobile);
        TextView email = rootView.findViewById(R.id.edit_email);
        TextInputEditText editFullname = rootView.findViewById(R.id.edit_fullname);
        TextInputEditText editPassword = rootView.findViewById(R.id.edit_password);
        TextView btnUpdate = rootView.findViewById(R.id.btn_update);

        mobile.setText("" + userLogin.getCcode() + userLogin.getMobile());
        email.setText("" + userLogin.getEmail());
        editFullname.setText("" + userLogin.getName());
        editPassword.setText("" + userLogin.getPassword());
        btnUpdate.setOnClickListener(v -> {
            mBottomSheetDialog.cancel();
            editProfile(editFullname.getText().toString(), editPassword.getText().toString());

        });
        mBottomSheetDialog.show();
    }

    private void editProfile(String name, String password) {


        JSONObject jsonObject = new JSONObject();
        try {
            jsonObject.put("uid", userLogin.getId());
            jsonObject.put("name", name);
            jsonObject.put("password", password);

            RequestBody bodyRequest = RequestBody.create(jsonObject.toString(), MediaType.parse(applicationjson));
            Call<JsonObject> call = APIClient.getInterface().profileEdit(bodyRequest);
            GetResult getResult = new GetResult(getActivity());
            getResult.setMyListener(this);
            getResult.callForLogin(call, "3");
            custPrograssbar.prograssCreate(getActivity());
        } catch (JSONException e) {
            e.printStackTrace();
        }
    }

    private void updateProfile() {
        custPrograssbar.prograssCreate(getActivity());

        List<MultipartBody.Part> parts = new ArrayList<>();

        parts.add(prepareFilePart("image0", selectImage));

        RequestBody uid = createPartFromString(sessionManager.getUserDetails().getId());

        RequestBody size = createPartFromString("" + parts.size());


        Call<JsonObject> call = APIClient.getInterface().profileImageUpdate(uid, parts, size);
        GetResult getResult = new GetResult(getActivity());
        getResult.setMyListener(this);
        getResult.callForLogin(call, "3");

    }

    @Override
    public void callback(JsonObject result, String callNo) {

        try {
            custPrograssbar.closePrograssBar();
            if (callNo.equalsIgnoreCase("3")) {
                Gson gson = new Gson();
                Login loginUser = gson.fromJson(result.toString(), Login.class);
                Toast.makeText(getActivity(), loginUser.getResponseMsg(), Toast.LENGTH_LONG).show();
                if (loginUser.getResult().equalsIgnoreCase("true")) {

                    sessionManager.setUserDetails(loginUser.getUserLogin());
                    sessionManager.setFloatData(wallet, Float.parseFloat(loginUser.getUserLogin().getWallet()));
                    sessionManager.setBooleanData(login, true);
                    sessionManager.setBooleanData(intro, true);

                    userLogin = sessionManager.getUserDetails();
                    txtName.setText("" + userLogin.getName());
                    txtRates.setText("" + userLogin.getEmail());


                }
            } else if (callNo.equalsIgnoreCase("profile")) {
                Gson gson = new Gson();
                Help help = gson.fromJson(result.toString(), Help.class);
                if (help.getResult().equalsIgnoreCase("true")) {
                    recyclerPage.setAdapter(new MyFaqAdepter(help.getPagelist()));
                }
            }
        } catch (Exception e) {
            e.printStackTrace();
        }

    }

    ActivityResultLauncher<Intent> launcher = registerForActivityResult(new ActivityResultContracts.StartActivityForResult(),
            result -> {
                if (result.getResultCode() == Activity.RESULT_OK) {

                    Uri imageUri = result.getData().getData();
                    try (InputStream inputStream = getActivity().getContentResolver().openInputStream(imageUri)) {
                        File outputDir = getActivity().getApplicationContext().getFilesDir();
                        File outputFile = File.createTempFile("image", null, outputDir);
                        try (OutputStream outputStream = new FileOutputStream(outputFile)) {
                            byte[] buffer = new byte[1024];
                            int bytesRead;
                            while ((bytesRead = inputStream.read(buffer)) != -1) {
                                outputStream.write(buffer, 0, bytesRead);
                            }
                        }

                        // Get the file path of the saved image file
                        String imagePath = outputFile.getAbsolutePath();
                        if (imagePath != null) {
                            selectImage = imagePath;

                            Glide.with(getActivity())
                                    .load(selectImage)
                                    .thumbnail(Glide.with(this).load(R.drawable.tprofile))
                                    .into(imgs);

                            updateProfile();
                        }
                    } catch (IOException e) {
                        e.printStackTrace();
                    }





                }
            });




    public class MyFaqAdepter extends RecyclerView.Adapter<MyFaqAdepter.ViewHolder> {
        private List<Pages> pagesListages;

        public MyFaqAdepter(List<Pages> pagesListages) {
            this.pagesListages = pagesListages;
        }

        @Override
        public MyFaqAdepter.ViewHolder onCreateViewHolder(ViewGroup parent,
                                                          int viewType) {

            View view = LayoutInflater.from(parent.getContext())
                    .inflate(R.layout.halp_item, parent, false);

            return new MyFaqAdepter.ViewHolder(view);
        }

        @Override
        public void onBindViewHolder(MyFaqAdepter.ViewHolder holder,
                                     int position) {
            Log.e("position", "" + position);
            Pages order = pagesListages.get(position);
            holder.txtTital.setText("" + order.getTitle());


            holder.lvlClick.setOnClickListener(v -> startActivity(new Intent(getActivity(), HelpDetailsActivity.class).putExtra("title", order.getTitle()).putExtra("desc", order.getDescription())));
        }

        @Override
        public int getItemCount() {
            return pagesListages.size();
        }

        public class ViewHolder extends RecyclerView.ViewHolder {
            @BindView(R.id.txt_title)
            TextView txtTital;
            @BindView(R.id.lvl_click)
            LinearLayout lvlClick;


            public ViewHolder(View view) {
                super(view);
                ButterKnife.bind(this, view);
            }
        }
    }

    public void bottonLanguageList() {
        BottomSheetDialog mBottomSheetDialog = new BottomSheetDialog(getActivity());
        View sheetView = getLayoutInflater().inflate(R.layout.custome_launguage, null);
        LinearLayout lvlenglish = sheetView.findViewById(R.id.lvl_english);
        LinearLayout lvlSpanish = sheetView.findViewById(R.id.lvl_spanish);
        LinearLayout lvlarb = sheetView.findViewById(R.id.lvl_arb);
        LinearLayout lvlHind = sheetView.findViewById(R.id.lvl_hind);
        LinearLayout lvlGujarat = sheetView.findViewById(R.id.lvl_gujarat);
        LinearLayout lvlIndonesiya = sheetView.findViewById(R.id.lvl_indonesiya);
        LinearLayout lvlBangali = sheetView.findViewById(R.id.lvl_bangali);
        LinearLayout lvlAfrikan = sheetView.findViewById(R.id.lvl_afrikan);

        lvlIndonesiya.setOnClickListener(v -> {
            mBottomSheetDialog.show();

            sessionManager.setStringData(language, "in");
            startActivity(new Intent(getActivity(), HomeActivity.class).setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK));
            getActivity().finish();
        });

        lvlBangali.setOnClickListener(v -> {
            mBottomSheetDialog.show();

            sessionManager.setStringData(language, "bn");
            startActivity(new Intent(getActivity(), HomeActivity.class).setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK));
            getActivity().finish();
        });

        lvlAfrikan.setOnClickListener(v -> {
            mBottomSheetDialog.show();

            sessionManager.setStringData(language, "af");
            startActivity(new Intent(getActivity(), HomeActivity.class).setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK));
            getActivity().finish();
        });

        lvlenglish.setOnClickListener(v -> {
            mBottomSheetDialog.show();

            sessionManager.setStringData(language, "en");
            startActivity(new Intent(getActivity(), HomeActivity.class).setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK));
            getActivity().finish();
        });

        lvlSpanish.setOnClickListener(v -> {
            mBottomSheetDialog.show();

            sessionManager.setStringData(language, "es");
            startActivity(new Intent(getActivity(), HomeActivity.class).setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK));
            getActivity().finish();


        });

        lvlHind.setOnClickListener(v -> {
            mBottomSheetDialog.show();

            sessionManager.setStringData(language, "hi");
            startActivity(new Intent(getActivity(), HomeActivity.class).setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK));
            getActivity().finish();


        });

        lvlarb.setOnClickListener(v -> {
            mBottomSheetDialog.show();

            sessionManager.setStringData(language, "ar");
            startActivity(new Intent(getActivity(), HomeActivity.class).setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK));
            getActivity().finish();


        });

        lvlGujarat.setOnClickListener(v -> {
            mBottomSheetDialog.show();

            sessionManager.setStringData(language, "gu");
            startActivity(new Intent(getActivity(), HomeActivity.class).setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK));
            getActivity().finish();


        });


        mBottomSheetDialog.setContentView(sheetView);
        mBottomSheetDialog.show();
    }
}