package com.springboot.mapper;

import com.springboot.pojo.User;
import org.apache.ibatis.annotations.Insert;
import org.apache.ibatis.annotations.Mapper;
import org.apache.ibatis.annotations.Select;
import org.springframework.stereotype.Component;

@Mapper
public interface UserMapper {

    @Insert("insert into `user` (custid, title, firstname, lastname, location, email, areacode, contact, password, terms) " +
            "values (#{custid},#{title},#{firstname},#{lastname},#{location},#{email},#{areacode},#{contact},#{password},#{terms})")
    int register(User user);

    @Select("select max(substring(custid, 2)) from `user`")
    String getMaxCustId();

    @Select("select * from `user` where email = #{email}")
    User findByEmail(String email);

    @Select("select * from `user` where email = #{email} and password = #{password}")
    User login(String email, String password);

}