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

    @GetMapping("/mainpage")
    public String showlanding(){
        return "Mainpage";
    }

    @GetMapping("/{page}")
    public String showPage(@PathVariable String page) {
        return page;
    }

    @PostMapping("login")
    public String loginFunction(User user, Model model){
        String errorMessage = null;
        try {
            // 检查用户输入是否为空
            if (user.getEmail() == null || user.getPassword() == null) {
                errorMessage = "Please enter email and password";
                model.addAttribute("errorMessage", errorMessage);
                return showPage("login");
            }

            User foundUser = userMapper.findByEmail(user.getEmail());
            if(foundUser == null) {
                errorMessage = "Email not found";
            } else {
                User loggedInUser = userMapper.login(user.getEmail(), user.getPassword());
                if(loggedInUser == null) {
                    errorMessage = "Wrong password";
                } else {
                    System.out.println(user.getFirstname());
                    return showlanding();
                }
            }
        } catch (Exception e) {
            e.printStackTrace();
            errorMessage = "Error";
        }
        model.addAttribute("errorMessage", errorMessage);
        return showPage("login");
    }

    @PostMapping("signup")
    public String sufnction(User user, Model model) {
        String errorMessage = null;
        try {
            // 检查用户输入是否为空
            if (user.getTitle() == null || user.getFirstname() == null || user.getLastname() == null || user.getLocation() == null ||
                    user.getEmail() == null || user.getAreacode() == null || user.getContact() == null || user.getPassword() == null || user.getTerms() == null) {
                errorMessage = "All fields are required";
                model.addAttribute("errorMessage", errorMessage);
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
                errorMessage = "Registration failed, please try again";
                model.addAttribute("errorMessage", errorMessage);
                return showPage("signup");
            }
        } catch (Exception e) {
            e.printStackTrace();
            errorMessage = "Error";
            model.addAttribute("errorMessage", errorMessage);
            return showPage("signup");
        }
    }
}
