package com.springboot.mapper;

import com.springboot.pojo.Product;
import com.springboot.pojo.User;
import org.apache.ibatis.annotations.Insert;
import org.apache.ibatis.annotations.Mapper;
import org.apache.ibatis.annotations.Select;

import java.util.List;

@Mapper
public interface FunctionMapper {

    @Insert("insert into `user` (custid, title, firstname, lastname, location, email, areacode, contact, password, terms) " +
            "values (#{custid},#{title},#{firstname},#{lastname},#{location},#{email},#{areacode},#{contact},#{password},#{terms})")
    int register(User user);

    @Select("select max(substring(custid, 2)) from `user`")
    String getMaxCustId();

    @Select("select * from `user` where email = #{email}")
    User findByEmail(String email);

    @Select("select * from `user` where email = #{email} and password = #{password}")
    User login(String email, String password);

    @Select("SELECT productimage FROM `product` where productid = #{id}")
    List<Product> getproductimage(String id);

    @Select("SELECT * FROM `product` WHERE productid = #{id}")
    Product getProductById(String id);
}
