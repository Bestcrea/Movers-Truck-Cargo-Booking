package com.cscodetech.movers.ui;

import static com.cscodetech.movers.utils.SessionManager.intro;
import static com.cscodetech.movers.utils.SessionManager.login;

import android.annotation.SuppressLint;
import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentManager;
import androidx.fragment.app.FragmentPagerAdapter;
import androidx.viewpager.widget.ViewPager;

import com.cscodetech.movers.R;
import com.cscodetech.movers.fragment.OneFragment;
import com.cscodetech.movers.fragment.TreeFragment;
import com.cscodetech.movers.fragment.TwoFragment;
import com.cscodetech.movers.model.UserLogin;
import com.google.android.material.tabs.TabLayout;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;

public class IntroActivity extends BaseActivity {


    ViewPager vpPager;
    MyPagerAdapter adapterViewPager;
    TextView btnNext;
    @BindView(R.id.btn_skip)
    TextView btnSkip;
    @BindView(R.id.txt_title)
    TextView txtTitle;
    @BindView(R.id.txt_subtitle)
    TextView txtSubtitle;
    int selectPage = 0;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_intro);
        ButterKnife.bind(this);
        btnNext = findViewById(R.id.btn_next);
        vpPager = findViewById(R.id.vpPager);

        if(sessionmanager.getBooleanData(login)){
            startActivity(new Intent(IntroActivity.this, HomeActivity.class));
            finish();
        }
        adapterViewPager = new MyPagerAdapter(getSupportFragmentManager());
        vpPager.setAdapter(adapterViewPager);
        TabLayout extensiblePageIndicator = findViewById(R.id.flexibleIndicator);
        extensiblePageIndicator.setupWithViewPager(vpPager);

        vpPager.addOnPageChangeListener(new ViewPager.OnPageChangeListener() {


            @Override
            public void onPageScrolled(int position, float positionOffset, int positionOffsetPixels) {
                Log.d("TAG", "onPageScrolled - position: " + position + ", positionOffset: " + positionOffset + ", positionOffsetPixels: " + positionOffsetPixels);
            }

            @SuppressLint("SetTextI18n")
            @Override
            public void onPageSelected(int position) {
                selectPage = position;
                switch (position) {
                    case 0:
                        btnSkip.setVisibility(View.VISIBLE);
                        btnNext.setText(R.string.next);
                        txtTitle.setText(R.string.onet);
                        txtSubtitle.setText(R.string.onest);
                        break;
                    case 1:
                        btnSkip.setVisibility(View.VISIBLE);
                        btnNext.setText(R.string.next);
                        txtTitle.setText(R.string.twot);
                        txtSubtitle.setText(R.string.twost);
                        break;
                    case 2:
                        btnSkip.setVisibility(View.GONE);
                        btnNext.setText(R.string.getstarted);
                        txtTitle.setText(R.string.treet);
                        txtSubtitle.setText(R.string.treest);
                        break;
                    default:
                        break;
                }
            }


            @Override
            public void onPageScrollStateChanged(int state) {
                Log.d("TAG", "onPageScrolled - position: " + state );
            }
        });
    }

    @OnClick({R.id.btn_next, R.id.btn_skip})
    public void onClick(View view) {
        switch (view.getId()) {
            case R.id.btn_next:
                if (selectPage == 0) {
                    vpPager.setCurrentItem(1);
                } else if (selectPage == 1) {
                    vpPager.setCurrentItem(2);
                } else if (selectPage == 2) {
                    sessionmanager.setBooleanData(intro, true);
                    UserLogin user = new UserLogin();
                    user.setId("0");
                    user.setName("User");
                    user.setEmail("user@gmail.com");
                    user.setMobile("+91 8888888888");
                    sessionmanager.setUserDetails(user);
                    startActivity(new Intent(IntroActivity.this, LoginActivity.class));
                    finish();
                }
                break;
            case R.id.btn_skip:
                sessionmanager.setBooleanData(intro, true);
                UserLogin user = new UserLogin();
                user.setId("0");
                user.setName("User");
                user.setEmail("user@gmail.com");
                user.setMobile("+91 8888888888");
                sessionmanager.setUserDetails(user);
                startActivity(new Intent(IntroActivity.this, LoginActivity.class));
                finish();
                break;
            default:
                break;
        }
    }

    public class MyPagerAdapter extends FragmentPagerAdapter {
        private int anInt = 3;

        public MyPagerAdapter(FragmentManager fragmentManager) {
            super(fragmentManager);
        }

        @Override
        public int getCount() {
            return anInt;
        }

        @Override
        public Fragment getItem(int position) {

            switch (position) {
                case 0:

                    return new OneFragment();
                case 1:

                    return new TwoFragment();
                case 2:

                    return new TreeFragment();
                default:
                    return null;
            }

        }

        @Override
        public CharSequence getPageTitle(int position) {
            Log.e("page", "" + position);
            return "";
        }

        @Override
        public Object instantiateItem(ViewGroup container, int position) {
            Fragment fragment = (Fragment) super.instantiateItem(container, position);
            return fragment;
        }

    }
}
