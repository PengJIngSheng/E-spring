package com.springboot.controller;

import com.springboot.mapper.UserMapper;
import com.springboot.pojo.User;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;

@Controller
@RequestMapping("/demo")
public class LoginController {
    @Autowired
    private UserMapper userMapper;

    @GetMapping("mainpage")
    public String Mainpage() {
        System.out.println("Accessing mainpage");
        return "Mainpage";
    }

    @GetMapping("/{page}")
    public String showPage(@PathVariable String page) {
        return page;
    }


    @GetMapping("pornhub")
    public String demopage(){
        System.out.println("demo page accces");
        return null;
    }


    @PostMapping("login")
    public String loginFunction(User user, Model model){
        String errorMessage = null;
        try {
            User foundUser = userMapper.findByEmail(user.getEmail());
            if(foundUser == null) {
                errorMessage = "Email not found";
            } else {
                User loggedInUser = userMapper.login(user.getEmail(), user.getPassword());
                if(loggedInUser == null) {
                    errorMessage = "Wrong password";
                } else {
                    System.out.println(user.getFirstname());
                    return Mainpage();
                }
            }
        } catch (Exception e) {
            e.printStackTrace();
            errorMessage = "error";
        }
        model.addAttribute("errorMessage", errorMessage);
        return showPage("signup");
    }


    @PostMapping("signup")
    public String sufnction(User user, Model model) {
        if (user.getTitle() == null || user.getFirstname() == null || user.getLastname() == null || user.getLocation() == null ||
                user.getEmail() == null || user.getAreacode() == null || user.getContact() == null || user.getPassword() == null || user.getTerms() == null) {
//            model.addAttribute("error", "所有字段都必须填写");
            return showPage("signup");
        }

        String maxCustId = userMapper.getMaxCustId();
        int userCount = maxCustId == null ? 1001 : Integer.parseInt(maxCustId) + 1;
        String userId = "C" + userCount;
        int affectedrows = userMapper.register(new User(userId, user.getTitle(), user.getFirstname(), user.getLastname(), user.getLocation(),
                user.getEmail(), user.getAreacode(), user.getContact(), user.getPassword(), user.getTerms()));
        if (affectedrows > 0) {
            return "login";
        } else {
            System.out.println("测试问题");
//            model.addAttribute("error", "注册失败，请重试");
            return showPage("signup");
        }
    }
}
