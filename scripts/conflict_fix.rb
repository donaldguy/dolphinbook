#!/usr/bin/ruby


require 'mysql'
require 'stringio'

$db = Mysql.new("mysql.donaldguy.com",:user,:password,"olsched")

class BlockConflictFinder
  
  attr_reader :conflicts
  
  def initialize(block)
    res = $db.query("SELECT teacher, block, COUNT(block) conflicts FROM classes WHERE block = '#{block}' GROUP BY teacher HAVING conflicts > 1");
    @conflicts = []
    res.each {|row| @conflicts << Conflict.new(row[0],row[1])}
  end 
end

class Conflict
  def initialize(teacher, block)
    res = $db.query <<EOQ
SELECT classes.id cid, classes.class name, classes.teacher teacher,  classes.block block, COUNT( students.#{block} ) in_class FROM classes, students 
WHERE teacher LIKE '#{teacher}'
AND classes.block = '#{block}'
AND students.#{block} = classes.id
GROUP BY students.#{block}
EOQ
    @ids = []
    @info = {}
    res.each_hash do |row|
      @ids << row['cid']
      @info[row.delete('cid')] = row;
    end
  end

  def to_s
    str = StringIO.new("","w")
    str.puts <<EOR
 ID     Name of class                    Teacher                 # of Students
EOR
    @ids.each do |cid|
     str.printf("%-4d   %-30s   %-20s   %9d\n",cid, @info[cid]['name'],@info[cid]['teacher'],@info[cid]['in_class'])
    end

    str.string
    
  end

  def remove(id)
    @info[id.to_s]['cid'] = @ids.delete(id.to_s)
    @info.delete(id.to_s)
  end

  def fix(id)
    id = id.to_s
    block = @info[id]['block']
    teacher = @info[id]['teacher']
    @ids.delete(id)
    $db.query( "UPDATE students SET #{block} = '#{id}' WHERE #{block} IN ('#{@ids.join("', '")}')")
   $db.query("DELETE FROM classes WHERE id IN ('#{@ids.join("', '")}')")

    initialize(teacher, block) #lets reflect reality
    
    return @info[id]
  end
end

    
